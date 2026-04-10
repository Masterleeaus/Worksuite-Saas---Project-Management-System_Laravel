<?php

namespace Modules\TitanPWA\Console\Commands;

use Illuminate\Console\Command;

/**
 * GenerateVapidKeysCommand
 *
 * Generates a VAPID key-pair for Web Push notifications.
 *
 * VAPID (Voluntary Application Server Identification for Web Push) keys are an
 * elliptic-curve (P-256) key pair encoded as URL-safe Base64.  This command
 * generates them using OpenSSL and writes the result to your .env file or
 * prints them to stdout.
 *
 * Usage:
 *   php artisan titanpwa:vapid-keys
 *   php artisan titanpwa:vapid-keys --print   (print only, don't update .env)
 */
class GenerateVapidKeysCommand extends Command
{
    protected $signature   = 'titanpwa:vapid-keys {--print : Print keys to console only (do not write to .env)}';
    protected $description = 'Generate VAPID key pair for TitanPWA Web Push notifications';

    public function handle(): int
    {
        if (! extension_loaded('openssl')) {
            $this->error('The openssl PHP extension is required to generate VAPID keys.');
            return self::FAILURE;
        }

        // Generate EC P-256 key pair
        $keyResource = openssl_pkey_new([
            'curve_name'       => 'prime256v1',
            'private_key_type' => OPENSSL_KEYTYPE_EC,
        ]);

        if (! $keyResource) {
            $this->error('Failed to generate EC key pair: ' . openssl_error_string());
            return self::FAILURE;
        }

        // Export private key in PEM format
        openssl_pkey_export($keyResource, $privatePem);
        $details = openssl_pkey_get_details($keyResource);

        if (! $details || ! isset($details['ec'])) {
            $this->error('Failed to extract key details.');
            return self::FAILURE;
        }

        // Raw public key (uncompressed point: 0x04 || x || y = 65 bytes)
        $publicKeyRaw = "\x04" . $details['ec']['x'] . $details['ec']['y'];

        // Raw private key (32 bytes)
        $privateKeyRaw = $details['ec']['d'];

        $publicKeyB64  = rtrim(strtr(base64_encode($publicKeyRaw), '+/', '-_'), '=');
        $privateKeyB64 = rtrim(strtr(base64_encode($privateKeyRaw), '+/', '-_'), '=');

        if ($this->option('print')) {
            $this->info('TITANPWA_VAPID_PUBLIC_KEY=' . $publicKeyB64);
            $this->info('TITANPWA_VAPID_PRIVATE_KEY=' . $privateKeyB64);
            return self::SUCCESS;
        }

        // Write to .env
        $envPath = base_path('.env');
        if (! file_exists($envPath)) {
            $this->warn('.env file not found; printing keys instead.');
            $this->info('TITANPWA_VAPID_PUBLIC_KEY=' . $publicKeyB64);
            $this->info('TITANPWA_VAPID_PRIVATE_KEY=' . $privateKeyB64);
            return self::SUCCESS;
        }

        $env = file_get_contents($envPath);

        $env = $this->updateEnvLine($env, 'TITANPWA_VAPID_PUBLIC_KEY',  $publicKeyB64);
        $env = $this->updateEnvLine($env, 'TITANPWA_VAPID_PRIVATE_KEY', $privateKeyB64);

        file_put_contents($envPath, $env);

        $this->info('✔ VAPID keys written to .env');
        $this->line('  Public key  : ' . $publicKeyB64);
        $this->line('  Private key : ' . $privateKeyB64);
        $this->newLine();
        $this->comment('The VAPID public key is exposed to clients via GET /api/titanpwa/push/vapid-key');

        return self::SUCCESS;
    }

    private function updateEnvLine(string $env, string $key, string $value): string
    {
        $pattern     = '/^' . preg_quote($key, '/') . '=.*/m';
        $replacement = $key . '=' . $value;

        if (preg_match($pattern, $env)) {
            return preg_replace($pattern, $replacement, $env);
        }

        return $env . PHP_EOL . $replacement;
    }
}
