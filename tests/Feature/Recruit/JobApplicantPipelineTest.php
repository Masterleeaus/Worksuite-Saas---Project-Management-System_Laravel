<?php

namespace Tests\Feature\Recruit;

use Modules\Recruit\Entities\JobApplicant;
use PHPUnit\Framework\TestCase;

/**
 * Feature-level tests for the JobApplicant pipeline that run without
 * a database connection, verifying pipeline-stage transitions and the
 * isHired() helper.
 *
 * Extends PHPUnit\Framework\TestCase (not Laravel's) — consistent with
 * other feature tests in this repository (e.g. GeofenceFeatureTest).
 */
class JobApplicantPipelineTest extends TestCase
{
    /** @test */
    public function applicant_statuses_constant_contains_all_required_pipeline_stages(): void
    {
        $statuses = array_keys(JobApplicant::STATUSES);

        $this->assertContains('applied',   $statuses);
        $this->assertContains('screening', $statuses);
        $this->assertContains('interview', $statuses);
        $this->assertContains('offer',     $statuses);
        $this->assertContains('hired',     $statuses);
        $this->assertContains('rejected',  $statuses);
    }

    /** @test */
    public function applicant_positions_include_cleaning_business_roles(): void
    {
        $positions = array_keys(JobApplicant::POSITIONS);

        $this->assertContains('cleaner',      $positions, 'Cleaner position must exist');
        $this->assertContains('supervisor',   $positions, 'Supervisor position must exist');
        $this->assertContains('area_manager', $positions, 'Area Manager position must exist');
        $this->assertContains('office',       $positions, 'Office position must exist');
    }

    /** @test */
    public function is_hired_returns_false_when_status_is_not_hired(): void
    {
        $applicant = new JobApplicant();
        $applicant->status = 'applied';
        $applicant->converted_employee_id = null;

        $this->assertFalse($applicant->isHired());
    }

    /** @test */
    public function is_hired_returns_false_when_status_hired_but_no_employee_id(): void
    {
        $applicant = new JobApplicant();
        $applicant->status = 'hired';
        $applicant->converted_employee_id = null;

        $this->assertFalse(
            $applicant->isHired(),
            'isHired() must return false when converted_employee_id is null'
        );
    }

    /** @test */
    public function is_hired_returns_true_when_status_hired_and_employee_id_set(): void
    {
        $applicant = new JobApplicant();
        // Set attributes directly to avoid Model::__construct filling
        $applicant->status = 'hired';
        $applicant->converted_employee_id = 42;

        $this->assertTrue($applicant->isHired());
    }

    /** @test */
    public function full_name_attribute_concatenates_first_and_last_name(): void
    {
        $applicant = new JobApplicant();
        $applicant->first_name = 'Jane';
        $applicant->last_name  = 'Smith';

        $this->assertSame('Jane Smith', $applicant->full_name);
    }

    /** @test */
    public function resume_disk_is_local_to_prevent_public_exposure(): void
    {
        $this->assertSame('local', JobApplicant::RESUME_DISK);
    }

    /** @test */
    public function converted_employee_id_is_set_on_hire_prevents_re_hire(): void
    {
        $applicant = new JobApplicant();
        $applicant->status = 'hired';
        $applicant->converted_employee_id = 99;

        // An already-hired applicant must be detected before re-triggering hire
        $this->assertTrue($applicant->isHired(), 'Already-hired applicant should be detected');
    }
}
