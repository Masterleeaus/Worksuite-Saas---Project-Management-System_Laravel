<?php

namespace Tests\Feature\Titan;

use App\Filament\Resources\ClientResource;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Titan\Support\TitanFakeUser;
use Tests\TestCase;

class TitanClientsResourceTest extends TestCase
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
            $table->unsignedBigInteger('is_client_contact')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->default('active');
            $table->string('password')->nullable();
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

        Schema::create('client_details', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('company_name')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->timestamps();
        });

        DB::table('companies')->insert([
            ['id' => 1, 'company_name' => 'Tenant A', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'company_name' => 'Tenant B', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('roles')->insert([
            ['id' => 1, 'company_id' => 1, 'name' => 'client', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'company_id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'company_id' => 1, 'name' => 'employee', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function test_clients_resource_enforces_tenant_isolation_for_list_query(): void
    {
        DB::table('users')->insert([
            ['id' => 101, 'name' => 'Tenant A Client', 'email' => 'a-client@example.test', 'company_id' => 1, 'status' => 'active', 'password' => 'x', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 202, 'name' => 'Tenant B Client', 'email' => 'b-client@example.test', 'company_id' => 2, 'status' => 'active', 'password' => 'x', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('role_user')->insert([
            ['role_id' => 1, 'user_id' => 101],
            ['role_id' => 1, 'user_id' => 202],
        ]);

        DB::table('client_details')->insert([
            ['user_id' => 101, 'company_id' => 1, 'company_name' => 'A Co', 'added_by' => 501, 'last_updated_by' => 501, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => 202, 'company_id' => 2, 'company_name' => 'B Co', 'added_by' => 502, 'last_updated_by' => 502, 'created_at' => now(), 'updated_at' => now()],
        ]);

        auth()->setUser(new TitanFakeUser(9001, 1, ['admin'], [
            'titan_access' => 'all',
            'view_clients' => 'all',
        ]));

        $ids = ClientResource::getEloquentQuery()->pluck('users.id')->all();

        $this->assertContains(101, $ids);
        $this->assertNotContains(202, $ids);
    }

    public function test_clients_resource_permissions_allow_admin_and_block_employee_and_guest(): void
    {
        auth()->logout();
        $this->assertFalse(ClientResource::canViewAny());

        auth()->setUser(new TitanFakeUser(9002, 1, ['employee'], [
            'titan_access' => false,
            'view_clients' => 'none',
        ]));
        $this->assertFalse(ClientResource::canViewAny());

        auth()->setUser(new TitanFakeUser(9003, 1, ['admin'], [
            'titan_access' => 'all',
            'view_clients' => 'all',
        ]));
        $this->assertTrue(ClientResource::canViewAny());
    }
}
