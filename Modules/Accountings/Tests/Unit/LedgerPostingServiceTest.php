<?php

namespace Modules\Accountings\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Modules\Accountings\Entities\FinancialTransaction;
use Modules\Accountings\Services\AccountingSignalService;
use Modules\Accountings\Services\LedgerPostingService;
use Tests\TestCase;

/**
 * Unit tests for LedgerPostingService::post().
 *
 * Verifies that transactions are persisted correctly and that the
 * account.transaction.posted signal is emitted for each posting.
 */
class LedgerPostingServiceTest extends TestCase
{
    use RefreshDatabase;

    private LedgerPostingService $service;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('acc_financial_transactions')) {
            Schema::create('acc_financial_transactions', function ($table) {
                $table->id();
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('invoice_id')->nullable();
                $table->unsignedBigInteger('payment_id')->nullable();
                $table->unsignedBigInteger('visit_cost_id')->nullable();
                $table->unsignedBigInteger('reversed_transaction_id')->nullable();
                $table->string('transaction_type', 60);
                $table->string('reference', 191)->nullable();
                $table->string('visit_ref', 191)->nullable();
                $table->string('job_ref', 191)->nullable();
                $table->string('site_ref', 191)->nullable();
                $table->string('contract_ref', 191)->nullable();
                $table->string('service_agreement_ref', 191)->nullable();
                $table->string('debit_account_code', 64)->nullable();
                $table->string('credit_account_code', 64)->nullable();
                $table->decimal('amount', 16, 2);
                $table->unsignedBigInteger('currency_id')->nullable();
                $table->timestamp('occurred_at')->nullable();
                $table->string('status', 40)->default('posted');
                $table->json('meta')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('acc_financial_signals')) {
            Schema::create('acc_financial_signals', function ($table) {
                $table->id();
                $table->uuid('signal_id')->unique();
                $table->string('signal_name', 191);
                $table->string('schema_version', 20)->default('1.0');
                $table->unsignedBigInteger('company_id');
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->string('source_type', 120)->nullable();
                $table->string('source_id', 191)->nullable();
                $table->string('correlation_id', 191)->nullable();
                $table->string('causation_id', 191)->nullable();
                $table->string('approval_mode', 30)->default('none');
                $table->string('risk_level', 30)->default('low');
                $table->timestamp('occurred_at')->nullable();
                $table->json('payload');
                $table->timestamps();
            });
        }

        $this->service = new LedgerPostingService(app(AccountingSignalService::class));
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('acc_financial_transactions');
        Schema::dropIfExists('acc_financial_signals');
        parent::tearDown();
    }

    /** @test */
    public function it_persists_a_financial_transaction(): void
    {
        $transaction = $this->service->post([
            'company_id'         => 1,
            'transaction_type'   => 'invoice_posted',
            'reference'          => 'INV-001',
            'amount'             => 250.00,
            'debit_account_code' => 'AR',
            'credit_account_code'=> 'REV',
        ]);

        $this->assertInstanceOf(FinancialTransaction::class, $transaction);
        $this->assertSame('INV-001', $transaction->reference);
        $this->assertSame('250.00', (string) $transaction->amount);
        $this->assertSame('posted', $transaction->status);
    }

    /** @test */
    public function it_emits_transaction_posted_signal(): void
    {
        $this->service->post([
            'company_id'       => 1,
            'transaction_type' => 'payment_received',
            'amount'           => 100.00,
        ]);

        $count = \Illuminate\Support\Facades\DB::table('acc_financial_signals')
            ->where('signal_name', 'account.transaction.posted')
            ->where('company_id', 1)
            ->count();

        $this->assertSame(1, $count);
    }

    /** @test */
    public function it_stores_meta_as_array(): void
    {
        $transaction = $this->service->post([
            'company_id'       => 1,
            'transaction_type' => 'invoice_posted',
            'amount'           => 50.00,
            'meta'             => ['source' => 'test', 'channel' => 'direct'],
        ]);

        $this->assertIsArray($transaction->meta);
        $this->assertSame('test', $transaction->meta['source']);
    }

    /** @test */
    public function it_defaults_status_to_posted(): void
    {
        $transaction = $this->service->post([
            'company_id'       => 1,
            'transaction_type' => 'adjustment',
            'amount'           => 10.00,
        ]);

        $this->assertSame('posted', $transaction->status);
    }

    /** @test */
    public function it_accepts_custom_status(): void
    {
        $transaction = $this->service->post([
            'company_id'       => 1,
            'transaction_type' => 'adjustment',
            'amount'           => 10.00,
            'status'           => 'pending_review',
        ]);

        $this->assertSame('pending_review', $transaction->status);
    }
}
