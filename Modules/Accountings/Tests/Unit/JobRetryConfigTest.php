<?php

namespace Modules\Accountings\Tests\Unit;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Accountings\Jobs\BuildMonthlyFinanceSummaryJob;
use Modules\Accountings\Jobs\DetectProfitabilityAnomaliesJob;
use Modules\Accountings\Jobs\GenerateAccountingSnapshotJob;
use Modules\Accountings\Jobs\RecalculateContractProfitabilityJob;
use Modules\Accountings\Jobs\RecalculateSiteProfitabilityJob;
use Modules\Accountings\Jobs\RecalculateVisitCostJob;
use Modules\Accountings\Jobs\SyncInvoiceLedgerEntriesJob;
use Tests\TestCase;

/**
 * Unit tests that verify queue, retry, timeout, and uniqueness configuration
 * on the accounting engine jobs.
 *
 * No database required – these tests inspect job class metadata only.
 */
class JobRetryConfigTest extends TestCase
{
    // ------------------------------------------------------------------
    // All jobs implement ShouldQueue
    // ------------------------------------------------------------------

    /** @test */
    public function all_accounting_jobs_are_queueable(): void
    {
        $jobs = [
            new BuildMonthlyFinanceSummaryJob(1),
            new GenerateAccountingSnapshotJob(1, 'SITE-A', 'CONTRACT-1'),
            new DetectProfitabilityAnomaliesJob(1),
            new RecalculateVisitCostJob(['company_id' => 1, 'visit_ref' => 'V-TEST']),
            new RecalculateSiteProfitabilityJob(1, 'SITE-A'),
            new RecalculateContractProfitabilityJob(1, 'CONTRACT-1'),
            new SyncInvoiceLedgerEntriesJob(42, 1),
        ];

        foreach ($jobs as $job) {
            $this->assertInstanceOf(ShouldQueue::class, $job, get_class($job) . ' must implement ShouldQueue');
        }
    }

    // ------------------------------------------------------------------
    // Long-running jobs carry retry/timeout metadata
    // ------------------------------------------------------------------

    /** @test */
    public function build_monthly_summary_job_has_retry_metadata(): void
    {
        $job = new BuildMonthlyFinanceSummaryJob(1);

        $this->assertSame(3, $job->tries, 'BuildMonthlyFinanceSummaryJob::$tries must be 3');
        $this->assertSame(600, $job->timeout, 'BuildMonthlyFinanceSummaryJob::$timeout must be 600');
        $this->assertNotEmpty($job->backoff);
    }

    /** @test */
    public function generate_snapshot_job_has_retry_metadata(): void
    {
        $job = new GenerateAccountingSnapshotJob(1, 'SITE-A', 'CONTRACT-1');

        $this->assertSame(3, $job->tries);
        $this->assertSame(300, $job->timeout);
        $this->assertNotEmpty($job->backoff);
    }

    /** @test */
    public function detect_anomalies_job_has_retry_metadata(): void
    {
        $job = new DetectProfitabilityAnomaliesJob(1);

        $this->assertSame(3, $job->tries);
        $this->assertSame(300, $job->timeout);
        $this->assertNotEmpty($job->backoff);
    }

    // ------------------------------------------------------------------
    // Idempotent long-running jobs implement ShouldBeUnique
    // ------------------------------------------------------------------

    /** @test */
    public function build_monthly_summary_job_is_unique(): void
    {
        $this->assertInstanceOf(ShouldBeUnique::class, new BuildMonthlyFinanceSummaryJob(1));
    }

    /** @test */
    public function generate_snapshot_job_is_unique(): void
    {
        $this->assertInstanceOf(ShouldBeUnique::class, new GenerateAccountingSnapshotJob(1, 'S', 'C'));
    }

    /** @test */
    public function detect_anomalies_job_is_unique(): void
    {
        $this->assertInstanceOf(ShouldBeUnique::class, new DetectProfitabilityAnomaliesJob(1));
    }

    // ------------------------------------------------------------------
    // Unique ID scoping
    // ------------------------------------------------------------------

    /** @test */
    public function unique_ids_are_scoped_by_company(): void
    {
        $jobA = new BuildMonthlyFinanceSummaryJob(1);
        $jobB = new BuildMonthlyFinanceSummaryJob(2);

        $this->assertNotSame($jobA->uniqueId(), $jobB->uniqueId());
    }

    /** @test */
    public function snapshot_unique_id_includes_site_and_contract(): void
    {
        $jobA = new GenerateAccountingSnapshotJob(1, 'SITE-A', 'CONTRACT-1');
        $jobB = new GenerateAccountingSnapshotJob(1, 'SITE-B', 'CONTRACT-1');

        $this->assertNotSame($jobA->uniqueId(), $jobB->uniqueId());
    }
}
