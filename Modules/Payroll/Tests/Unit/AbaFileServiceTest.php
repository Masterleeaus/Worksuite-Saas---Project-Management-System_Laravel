<?php

namespace Modules\Payroll\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Modules\Payroll\Services\AbaFileService;

/**
 * Unit tests for AbaFileService — ABA bank file generation and validation.
 *
 * These tests do NOT require a database connection.
 */
class AbaFileServiceTest extends TestCase
{
    private AbaFileService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AbaFileService();
    }

    // -------------------------------------------------------------------------
    // BSB validation
    // -------------------------------------------------------------------------

    /** @test */
    public function valid_bsb_passes_validation(): void
    {
        $this->service->validateBsb('062-001'); // Should not throw
        $this->service->validateBsb('033-000');
        $this->service->validateBsb('182-512');
        $this->assertTrue(true); // reached without exception
    }

    /** @test */
    public function bsb_without_hyphen_fails_validation(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->validateBsb('062001');
    }

    /** @test */
    public function bsb_with_wrong_format_fails_validation(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->validateBsb('06-2001');
    }

    /** @test */
    public function bsb_with_letters_fails_validation(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->validateBsb('ABC-DEF');
    }

    // -------------------------------------------------------------------------
    // Account number validation
    // -------------------------------------------------------------------------

    /** @test */
    public function valid_account_number_passes_validation(): void
    {
        $this->service->validateAccountNumber('123456789');
        $this->service->validateAccountNumber('1');
        $this->service->validateAccountNumber('000000001');
        $this->assertTrue(true);
    }

    /** @test */
    public function account_number_with_letters_fails_validation(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->validateAccountNumber('1234ABC');
    }

    // -------------------------------------------------------------------------
    // ABA file generation
    // -------------------------------------------------------------------------

    /** @test */
    public function generates_valid_aba_file_with_correct_line_lengths(): void
    {
        $header = $this->sampleHeader();
        $records = [$this->sampleRecord()];

        $aba = $this->service->generate($header, $records);

        // Split on CRLF
        $lines = explode("\r\n", rtrim($aba, "\r\n"));

        // Must have at least 3 lines: header + 1 record + trailer
        $this->assertGreaterThanOrEqual(3, count($lines));

        foreach ($lines as $line) {
            $this->assertEquals(120, strlen($line), "ABA line must be exactly 120 chars, got: " . strlen($line));
        }
    }

    /** @test */
    public function header_record_starts_with_zero(): void
    {
        $aba   = $this->service->generate($this->sampleHeader(), [$this->sampleRecord()]);
        $lines = explode("\r\n", rtrim($aba, "\r\n"));

        $this->assertEquals('0', $lines[0][0], 'Header record type must be 0');
    }

    /** @test */
    public function detail_record_starts_with_one(): void
    {
        $aba   = $this->service->generate($this->sampleHeader(), [$this->sampleRecord()]);
        $lines = explode("\r\n", rtrim($aba, "\r\n"));

        $this->assertEquals('1', $lines[1][0], 'Detail record type must be 1');
    }

    /** @test */
    public function trailer_record_starts_with_seven(): void
    {
        $aba   = $this->service->generate($this->sampleHeader(), [$this->sampleRecord()]);
        $lines = explode("\r\n", rtrim($aba, "\r\n"));

        $this->assertEquals('7', $lines[count($lines) - 1][0], 'Trailer record type must be 7');
    }

    /** @test */
    public function generate_throws_when_no_records(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->service->generate($this->sampleHeader(), []);
    }

    /** @test */
    public function generate_throws_on_invalid_bsb_in_record(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $record = $this->sampleRecord();
        $record['bsb'] = 'invalid';
        $this->service->generate($this->sampleHeader(), [$record]);
    }

    /** @test */
    public function multiple_records_produce_correct_line_count(): void
    {
        $records = [
            $this->sampleRecord(),
            array_merge($this->sampleRecord(), ['account_number' => '987654321', 'amount_cents' => 5000]),
            array_merge($this->sampleRecord(), ['account_number' => '111222333', 'amount_cents' => 25000]),
        ];

        $aba   = $this->service->generate($this->sampleHeader(), $records);
        $lines = array_filter(explode("\r\n", rtrim($aba, "\r\n")));

        // header + 3 records + trailer = 5 lines
        $this->assertCount(5, $lines);
    }

    /** @test */
    public function employee_bank_details_appear_in_record(): void
    {
        $record = $this->sampleRecord();
        $record['account_name'] = 'JOHN SMITH';

        $aba   = $this->service->generate($this->sampleHeader(), [$record]);
        $lines = explode("\r\n", rtrim($aba, "\r\n"));
        $detailLine = $lines[1];

        $this->assertStringContainsString('JOHN SMITH', $detailLine);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function sampleHeader(): array
    {
        return [
            'bsb'             => '062-001',
            'account_number'  => '123456789',
            'bank_name'       => 'CBA',
            'user_name'       => 'TEST COMPANY PTY LTD',
            'user_number'     => '123456',
            'description'     => 'PAYROLL',
            'processing_date' => '120426',
        ];
    }

    private function sampleRecord(): array
    {
        return [
            'bsb'            => '033-000',
            'account_number' => '123456789',
            'account_name'   => 'JANE DOE',
            'amount_cents'   => 150000, // $1,500.00
            'lodgement_ref'  => 'PAY 2026-04-12',
            'trace_bsb'      => '062-001',
            'trace_account'  => '123456789',
            'remitter_name'  => 'TEST COMPANY',
            'tax_amount'     => 30000, // $300.00
        ];
    }
}
