<?php

namespace Tests\Feature\Titan;

use App\Filament\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Titan\Support\TitanFakeUser;
use Tests\TestCase;

class TitanProjectsResourceTest extends TestCase
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

        Schema::create('projects', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('project_name');
            $table->unsignedBigInteger('project_admin')->nullable();
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('status')->default('in progress');
            $table->unsignedInteger('completion_percent')->default(0);
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('project_members', function ($table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('mention_users', function ($table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::create('invoices', function ($table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->timestamps();
        });

        Schema::create('tasks', function ($table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->timestamps();
        });

        DB::table('companies')->insert([
            ['id' => 1, 'company_name' => 'Tenant A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'company_name' => 'Tenant B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('users')->insert([
            ['id' => 11, 'name' => 'Admin A', 'email' => 'admin-a@example.test', 'company_id' => 1, 'password' => 'x', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'name' => 'Member A', 'email' => 'member-a@example.test', 'company_id' => 1, 'password' => 'x', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'name' => 'Admin B', 'email' => 'admin-b@example.test', 'company_id' => 2, 'password' => 'x', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_projects_resource_enforces_tenant_isolation_for_list_query(): void
    {
        DB::table('projects')->insert([
            ['id' => 1001, 'company_id' => 1, 'project_name' => 'Tenant A Project', 'project_admin' => 11, 'client_id' => 11, 'start_date' => now()->toDateString(), 'status' => 'in progress', 'completion_percent' => 30, 'added_by' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2002, 'company_id' => 2, 'project_name' => 'Tenant B Project', 'project_admin' => 21, 'client_id' => 21, 'start_date' => now()->toDateString(), 'status' => 'in progress', 'completion_percent' => 40, 'added_by' => 21, 'created_at' => now(), 'updated_at' => now()],
        ]);

        auth()->setUser(new TitanFakeUser(7001, 1, ['admin'], [
            'titan_access' => 'all',
            'view_projects' => 'all',
        ]));

        $ids = ProjectResource::getEloquentQuery()->pluck('projects.id')->all();

        $this->assertContains(1001, $ids);
        $this->assertNotContains(2002, $ids);
    }

    public function test_projects_resource_permissions_allow_admin_and_restrict_employee_and_guest(): void
    {
        DB::table('projects')->insert([
            ['id' => 1001, 'company_id' => 1, 'project_name' => 'Tenant A Project', 'project_admin' => 11, 'client_id' => 11, 'start_date' => now()->toDateString(), 'status' => 'in progress', 'completion_percent' => 30, 'added_by' => 11, 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('project_members')->insert([
            ['project_id' => 1001, 'user_id' => 12, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $project = Project::query()->findOrFail(1001);

        auth()->logout();
        $this->assertFalse(ProjectResource::canViewAny());

        auth()->setUser(new TitanFakeUser(7002, 1, ['employee'], [
            'titan_access' => false,
            'view_projects' => 'none',
            'edit_projects' => 'none',
            'add_projects' => 'none',
        ]));
        $this->assertFalse(ProjectResource::canViewAny());
        $this->assertFalse(ProjectResource::canView($project));

        auth()->setUser(new TitanFakeUser(7003, 1, ['admin'], [
            'titan_access' => 'all',
            'view_projects' => 'all',
        ]));
        $this->assertTrue(ProjectResource::canViewAny());
        $this->assertTrue(ProjectResource::canView($project));
    }
}
