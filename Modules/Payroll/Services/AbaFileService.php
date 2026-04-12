<?php

namespace Modules\Payroll\Services;

use InvalidArgumentException;

/**
 * AbaFileService — generates Australian Banking Association (ABA) bulk-payment files.
 *
 * Specification: ABA (DE) direct entry file format.
 * - One 120-char header record (Type 0)
 * - One or more 120-char detail records (Type 1)
 * - One 120-char trailer/total record (Type 7)
 *
 * All string fields are padded with spaces to exact field widths.
 */
class AbaFileService
{
    // Validation patterns
    private const BSB_PATTERN     = '/^\d{3}-\d{3}$/';
    private const ACCOUNT_PATTERN = '/^\d{1,9}$/';

    /**
     * Validate a BSB string in format NNN-NNN.
     *
     * @throws InvalidArgumentException
     */
    public function validateBsb(string $bsb): void
    {
        if (!preg_match(self::BSB_PATTERN, $bsb)) {
            throw new InvalidArgumentException(
                "Invalid BSB format '{$bsb}'. Expected NNN-NNN (e.g. 062-001)."
            );
        }
    }

    /**
     * Validate a bank account number (1–9 digits).
     *
     * @throws InvalidArgumentException
     */
    public function validateAccountNumber(string $account): void
    {
        $clean = preg_replace('/\s/', '', $account);
        if (!preg_match(self::ACCOUNT_PATTERN, $clean)) {
            throw new InvalidArgumentException(
                "Invalid account number '{$account}'. Must be 1–9 digits."
            );
        }
    }

    /**
     * Generate an ABA file string.
     *
     * @param  array  $header   {
     *     'bsb'             => string,   // bank BSB NNN-NNN
     *     'account_number'  => string,   // bank account (up to 9 digits)
     *     'bank_name'       => string,   // 3-char bank abbreviation e.g. 'CBA'
     *     'user_name'       => string,   // up to 26 chars
     *     'user_number'     => string,   // APCA user number (6 digits)
     *     'description'     => string,   // up to 12 chars
     *     'processing_date' => string,   // DDMMYY
     * }
     * @param  array  $records  Each element: {
     *     'bsb'            => string,   // NNN-NNN
     *     'account_number' => string,   // up to 9 digits
     *     'account_name'   => string,   // up to 32 chars
     *     'amount_cents'   => int,      // amount in cents (positive integer)
     *     'lodgement_ref'  => string,   // up to 18 chars (employee name / ref)
     *     'trace_bsb'      => string,   // originating BSB NNN-NNN
     *     'trace_account'  => string,   // originating account
     *     'remitter_name'  => string,   // up to 16 chars
     *     'tax_amount'     => int,       // withholding tax in cents (default 0)
     * }
     * @return string  ABA file content (use CRLF line endings as per spec)
     */
    public function generate(array $header, array $records): string
    {
        if (empty($records)) {
            throw new InvalidArgumentException('At least one payment record is required.');
        }

        $lines = [];
        $lines[] = $this->buildHeader($header);

        $totalCredit = 0;
        $totalDebit  = 0;

        foreach ($records as $record) {
            $this->validateBsb($record['bsb']);
            $this->validateAccountNumber(preg_replace('/\s/', '', $record['account_number']));

            $lines[]      = $this->buildRecord($record);
            $totalCredit += (int) $record['amount_cents'];
        }

        $lines[] = $this->buildTrailer($header, $totalCredit, $totalDebit, count($records));

        return implode("\r\n", $lines) . "\r\n";
    }

    // -------------------------------------------------------------------------
    // Private builders
    // -------------------------------------------------------------------------

    private function buildHeader(array $h): string
    {
        // Validate originating BSB
        $this->validateBsb($h['bsb']);

        // ABA Descriptive Record (Type 0) — official field layout (120 chars):
        // Pos  1     (1):  Record type '0'
        // Pos  2-18  (17): Blank (unused in modern DE)
        // Pos  19-20  (2): Sequence number '01'
        // Pos  21-23  (3): Bank/financial institution code (e.g. 'CBA','NAB','ANZ')
        // Pos  24-30  (7): Spaces (reserved)
        // Pos  31-56 (26): DE user's preferred name (left-justified, space-filled)
        // Pos  57-62  (6): APCA user identification number (right-justified, zero-filled)
        // Pos  63-74 (12): Description of entries (left-justified, space-filled)
        // Pos  75-80  (6): Date to be processed DDMMYY
        // Pos  81-84  (4): Time of processing HHMM (optional, spaces if unused)
        // Pos  85-120 (36): Reserved (spaces)
        // Total: 120 chars

        $line  = '0';                                                         //  1 (1)
        $line .= str_repeat(' ', 17);                                         //  2-18 (17)
        $line .= '01';                                                        //  19-20 (2)
        $line .= $this->pad($h['bank_name'] ?? 'NAB', 3);                    //  21-23 (3)
        $line .= str_repeat(' ', 7);                                          //  24-30 (7)
        $line .= $this->pad($h['user_name'] ?? 'PAYROLL', 26);               //  31-56 (26)
        $line .= $this->padLeft($h['user_number'] ?? '999999', 6, '0');      //  57-62 (6)
        $line .= $this->pad($h['description'] ?? 'PAYROLL', 12);             //  63-74 (12)
        // ABA spec requires DDMMYY (6 chars). PHP 'd'=2-digit day, 'm'=2-digit numeric
        // month (01-12), 'y'=2-digit year — 'dmy' produces the correct 6-char format.
        $line .= $this->pad($h['processing_date'] ?? now()->format('dmy'), 6); // 75-80 (6)
        $line .= str_repeat(' ', 4);                                          //  81-84 (4)
        $line .= str_repeat(' ', 36);                                         //  85-120 (36)

        return $this->assertLength($line, 120, 'header');
    }

    private function buildRecord(array $r): string
    {
        // ABA Detail Record (Type 1) — official field layout (120 chars):
        // Pos  1     (1):  Record type '1'
        // Pos  2-8   (7):  BSB number of target account (NNN-NNN)
        // Pos  9-17  (9):  Target account number (right-justified, space-filled)
        // Pos  18    (1):  Indicator: 'N'=normal, 'T'=tax withholding
        // Pos  19-20 (2):  Transaction code: '50'=credit, '13'=debit
        // Pos  21-30 (10): Amount in cents (right-justified, zero-filled)
        // Pos  31-62 (32): Account title (left-justified, space-filled)
        // Pos  63-80 (18): Lodgement reference (left-justified, space-filled)
        // Pos  81-87  (7): Trace BSB (originating bank, NNN-NNN)
        // Pos  88-96  (9): Trace account number (right-justified, space-filled)
        // Pos  97-112 (16): Remitter's name (left-justified, space-filled)
        // Pos  113-120 (8): Withholding tax amount in cents (right-justified, zeros)
        // Total: 120 chars

        $bsb        = $r['bsb'];                                                   //  7
        $account    = str_pad(preg_replace('/\s/', '', $r['account_number'] ?? ''), 9, ' ', STR_PAD_LEFT); //  9
        $indicator  = $r['indicator'] ?? 'N';                                      //  1
        $tranCode   = str_pad($r['tran_code'] ?? '50', 2);                         //  2
        $amount     = str_pad((string)(int)($r['amount_cents'] ?? 0), 10, '0', STR_PAD_LEFT); // 10
        $accName    = $this->pad($r['account_name'] ?? '', 32);                    // 32
        $lodgement  = $this->pad($r['lodgement_ref'] ?? '', 18);                   // 18
        $traceBsb   = $r['trace_bsb'] ?? $bsb;                                    //  7
        $traceAcc   = str_pad(preg_replace('/\s/', '', $r['trace_account'] ?? '000000000'), 9, ' ', STR_PAD_LEFT); //  9
        $remitter   = $this->pad($r['remitter_name'] ?? '', 16);                   // 16
        $tax        = str_pad((string)(int)($r['tax_amount'] ?? 0), 8, '0', STR_PAD_LEFT); //  8

        $line  = '1';         //  1
        $line .= $bsb;        //  7 → total  8
        $line .= $account;    //  9 → total 17
        $line .= $indicator;  //  1 → total 18
        $line .= $tranCode;   //  2 → total 20
        $line .= $amount;     // 10 → total 30
        $line .= $accName;    // 32 → total 62
        $line .= $lodgement;  // 18 → total 80
        $line .= $traceBsb;   //  7 → total 87
        $line .= $traceAcc;   //  9 → total 96
        $line .= $remitter;   // 16 → total 112
        $line .= $tax;        //  8 → total 120

        return $this->assertLength($line, 120, 'detail record');
    }

    private function buildTrailer(array $h, int $totalCredit, int $totalDebit, int $count): string
    {
        // ABA Batch Control Record (Type 7) — official field layout (120 chars):
        // Pos  1     (1):  Record type '7'
        // Pos  2-8   (7):  BSB filler '999-999'
        // Pos  9-20  (12): Blank
        // Pos  21-30 (10): Batch net total amount (right-justified, zero-filled)
        // Pos  31-40 (10): Batch credit total (right-justified, zero-filled)
        // Pos  41-50 (10): Batch debit total (right-justified, zero-filled)
        // Pos  51-74 (24): Blank (reserved)
        // Pos  75-80  (6): Number of records in file (right-justified, zero-filled)
        // Pos  81-120 (40): Blank (reserved)
        // Total: 120 chars

        $net   = abs($totalCredit - $totalDebit);

        $line  = '7';                                                        //  1 (1)
        $line .= '999-999';                                                  //  2-8 (7)
        $line .= str_repeat(' ', 12);                                        //  9-20 (12)
        $line .= str_pad((string) $net, 10, '0', STR_PAD_LEFT);             //  21-30 (10)
        $line .= str_pad((string) $totalCredit, 10, '0', STR_PAD_LEFT);     //  31-40 (10)
        $line .= str_pad((string) $totalDebit, 10, '0', STR_PAD_LEFT);      //  41-50 (10)
        $line .= str_repeat(' ', 24);                                        //  51-74 (24)
        $line .= str_pad((string) $count, 6, '0', STR_PAD_LEFT);            //  75-80 (6)
        $line .= str_repeat(' ', 40);                                        //  81-120 (40)

        return $this->assertLength($line, 120, 'trailer');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function pad(string $value, int $length): string
    {
        $truncated = mb_substr($value, 0, $length);
        return str_pad($truncated, $length);
    }

    private function padLeft(string $value, int $length, string $padChar = ' '): string
    {
        return str_pad(mb_substr($value, 0, $length), $length, $padChar, STR_PAD_LEFT);
    }

    /**
     * @throws InvalidArgumentException if line length != expected
     */
    private function assertLength(string $line, int $expected, string $context): string
    {
        $actual = strlen($line);
        if ($actual !== $expected) {
            throw new InvalidArgumentException(
                "ABA {$context} record must be exactly {$expected} chars, got {$actual}."
            );
        }
        return $line;
    }
}
