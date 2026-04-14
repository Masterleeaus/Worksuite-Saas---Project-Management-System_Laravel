<?php

namespace Tests\Feature\Titan;

use App\Filament\Resources\AttendanceResource;
use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\LeaveResource;
use App\Filament\Resources\ProjectFileResource;
use App\Filament\Resources\ProjectNoteResource;
use App\Filament\Resources\TimeLogResource;
use App\Models\Attendance;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Titan\Support\TitanFakeUser;
use Tests\TestCase;

class TitanTierTwoResourcesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::dropAllTables();

        Schema::create('companies', function ($table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('roles', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('role_user', function ($table) {
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('employee_details', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->string('employee_id')->nullable();
            $table->date('joining_date')->nullable();
            $table->timestamps();
        });

        Schema::create('attendances', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->date('date')->nullable();
            $table->dateTime('clock_in_time')->nullable();
            $table->dateTime('clock_out_time')->nullable();
            $table->string('working_from')->nullable();
            $table->string('work_from_type')->nullable();
            $table->string('late')->default('no');
            $table->string('half_day')->default('no');
            $table->timestamps();
        });

        Schema::create('project_time_logs', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->integer('total_minutes')->default(0);
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedTinyInteger('approved')->default(0);
            $table->timestamps();
        });

        Schema::create('leave_types', function ($table) {
            $table->id();
            $table->string('type_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leaves', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('leave_type_id')->nullable();
            $table->string('duration')->nullable();
            $table->date('leave_date')->nullable();
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('project_name')->nullable();
            $table->timestamps();
        });

        Schema::create('project_notes', function ($table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('title')->nullable();
            $table->text('details')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedTinyInteger('is_client_show')->default(0);
            $table->timestamps();
        });

        Schema::create('project_files', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->string('filename')->nullable();
            $table->string('hashname')->nullable();
            $table->string('size')->nullable();
            $table->timestamps();
        });

        DB::table('companies')->insert([
            ['id' => 1, 'company_name' => 'Tenant A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'company_name' => 'Tenant B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('roles')->insert([
            ['id' => 1, 'company_id' => 1, 'name' => 'employee', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'company_id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'company_id' => 2, 'name' => 'employee', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('users')->insert([
            ['id' => 11, 'company_id' => 1, 'name' => 'Tenant A Employee', 'email' => 'a-employee@example.test', 'password' => 'x', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'company_id' => 1, 'name' => 'Tenant A Admin', 'email' => 'a-admin@example.test', 'password' => 'x', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'company_id' => 2, 'name' => 'Tenant B Employee', 'email' => 'b-employee@example.test', 'password' => 'x', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('role_user')->insert([
            ['role_id' => 1, 'user_id' => 11],
            ['role_id' => 2, 'user_id' => 12],
            ['role_id' => 3, 'user_id' => 21],
        ]);

        DB::table('employee_details')->insert([
            ['user_id' => 11, 'company_id' => 1, 'employee_id' => 'EMP-A-1', 'joining_date' => now()->toDateString(), 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 21, 'company_id' => 2, 'employee_id' => 'EMP-B-1', 'joining_date' => now()->toDateString(), 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('leave_types')->insert([
            ['id' => 1, 'type_name' => 'Annual', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_tier_two_resources_are_tenant_scoped_for_list_queries(): void
    {
        DB::table('attendances')->insert([
            ['id' => 1001, 'company_id' => 1, 'user_id' => 11, 'date' => now()->toDateString(), 'clock_in_time' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2002, 'company_id' => 2, 'user_id' => 21, 'date' => now()->toDateString(), 'clock_in_time' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('project_time_logs')->insert([
            ['id' => 1001, 'company_id' => 1, 'user_id' => 11, 'start_time' => now(), 'total_minutes' => 60, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2002, 'company_id' => 2, 'user_id' => 21, 'start_time' => now(), 'total_minutes' => 60, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('leaves')->insert([
            ['id' => 1001, 'company_id' => 1, 'user_id' => 11, 'leave_type_id' => 1, 'duration' => 'full day', 'leave_date' => now()->toDateString(), 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2002, 'company_id' => 2, 'user_id' => 21, 'leave_type_id' => 1, 'duration' => 'full day', 'leave_date' => now()->toDateString(), 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('projects')->insert([
            ['id' => 1001, 'company_id' => 1, 'project_name' => 'A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2002, 'company_id' => 2, 'project_name' => 'B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('project_notes')->insert([
            ['id' => 1001, 'project_id' => 1001, 'title' => 'Tenant A Note', 'details' => 'A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2002, 'project_id' => 2002, 'title' => 'Tenant B Note', 'details' => 'B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('project_files')->insert([
            ['id' => 1001, 'company_id' => 1, 'project_id' => 1001, 'user_id' => 11, 'filename' => 'a.pdf', 'hashname' => 'a.pdf', 'size' => '100', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2002, 'company_id' => 2, 'project_id' => 2002, 'user_id' => 21, 'filename' => 'b.pdf', 'hashname' => 'b.pdf', 'size' => '100', 'created_at' => now(), 'updated_at' => now()],
        ]);

        auth()->setUser(new TitanFakeUser(9001, 1, ['admin'], [
            'titan_access' => 'all',
            'view_employees' => 'all',
            'view_attendance' => 'all',
            'view_timelogs' => 'all',
            'view_leave' => 'all',
            'view_project_note' => 'all',
            'view_project_files' => 'all',
        ]));

        $this->assertSame([11], EmployeeResource::getEloquentQuery()->pluck('users.id')->all());
        $this->assertSame([1001], AttendanceResource::getEloquentQuery()->pluck('attendances.id')->all());
        $this->assertSame([1001], TimeLogResource::getEloquentQuery()->pluck('project_time_logs.id')->all());
        $this->assertSame([1001], LeaveResource::getEloquentQuery()->pluck('leaves.id')->all());
        $this->assertSame([1001], ProjectNoteResource::getEloquentQuery()->pluck('project_notes.id')->all());
        $this->assertSame([1001], ProjectFileResource::getEloquentQuery()->pluck('project_files.id')->all());
    }

    public function test_guest_is_blocked_and_admin_is_allowed_for_tier_two_resources(): void
    {
        auth()->logout();

        $this->assertFalse(EmployeeResource::canViewAny());
        $this->assertFalse(AttendanceResource::canViewAny());
        $this->assertFalse(TimeLogResource::canViewAny());
        $this->assertFalse(LeaveResource::canViewAny());
        $this->assertFalse(ProjectNoteResource::canViewAny());
        $this->assertFalse(ProjectFileResource::canViewAny());

        auth()->setUser(new TitanFakeUser(9002, 1, ['admin'], [
            'titan_access' => 'all',
            'view_employees' => 'all',
            'view_attendance' => 'all',
            'view_timelogs' => 'all',
            'view_leave' => 'all',
            'view_project_note' => 'all',
            'view_project_files' => 'all',
        ]));

        $this->assertTrue(EmployeeResource::canViewAny());
        $this->assertTrue(AttendanceResource::canViewAny());
        $this->assertTrue(TimeLogResource::canViewAny());
        $this->assertTrue(LeaveResource::canViewAny());
        $this->assertTrue(ProjectNoteResource::canViewAny());
        $this->assertTrue(ProjectFileResource::canViewAny());
    }

    public function test_owned_attendance_permission_is_self_only(): void
    {
        DB::table('attendances')->insert([
            ['id' => 1001, 'company_id' => 1, 'user_id' => 11, 'date' => now()->toDateString(), 'clock_in_time' => now(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 1002, 'company_id' => 1, 'user_id' => 12, 'date' => now()->toDateString(), 'clock_in_time' => now(), 'created_at' => now(), 'updated_at' => now()],
        ]);

        $ownRecord = Attendance::query()->findOrFail(1001);
        $otherRecord = Attendance::query()->findOrFail(1002);

        auth()->setUser(new TitanFakeUser(11, 1, ['employee'], [
            'titan_access' => 'all',
            'view_attendance' => 'owned',
        ]));

        $this->assertTrue(AttendanceResource::canViewAny());
        $this->assertTrue(AttendanceResource::canView($ownRecord));
        $this->assertFalse(AttendanceResource::canView($otherRecord));
    }
}
