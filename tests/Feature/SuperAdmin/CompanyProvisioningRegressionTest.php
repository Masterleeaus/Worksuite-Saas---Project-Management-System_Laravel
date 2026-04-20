<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Company;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Titan\Support\TitanFakeUser;
use Tests\TestCase;

class CompanyProvisioningRegressionTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::dropAllTables();

        Schema::create('global_settings', function ($table) {
            $table->id();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_format')->default('H:i:s');
            $table->timestamps();
        });

        Schema::create('global_currencies', function ($table) {
            $table->id();
            $table->string('currency_name');
            $table->string('currency_symbol')->nullable();
            $table->string('currency_code');
            $table->decimal('exchange_rate', 10, 4)->nullable();
            $table->string('currency_position')->default('left');
            $table->integer('no_of_decimal')->default(2);
            $table->string('thousand_separator')->nullable();
            $table->string('decimal_separator')->nullable();
            $table->string('is_cryptocurrency')->default('no');
            $table->decimal('usd_price', 12, 4)->nullable();
            $table->timestamps();
        });

        Schema::create('companies', function ($table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('currencies', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('currency_name');
            $table->string('currency_symbol')->nullable();
            $table->string('currency_code');
            $table->decimal('exchange_rate', 10, 4)->nullable();
            $table->string('currency_position')->default('left');
            $table->integer('no_of_decimal')->default(2);
            $table->string('thousand_separator')->nullable();
            $table->string('decimal_separator')->nullable();
            $table->string('is_cryptocurrency')->default('no');
            $table->decimal('usd_price', 12, 4)->nullable();
            $table->timestamps();
        });

        Schema::create('roles', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('users', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('user_auth_id')->nullable();
            $table->unsignedBigInteger('is_client_contact')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->default('active');
            $table->unsignedTinyInteger('is_superadmin')->default(0);
            $table->string('locale')->default('en');
            $table->timestamps();
        });

        Schema::create('role_user', function ($table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['user_id', 'role_id']);
        });

        Schema::create('employee_details', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('employee_id')->nullable();
            $table->timestamps();
        });

        Schema::create('sessions', function ($table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('payload')->nullable();
            $table->integer('last_activity')->nullable();
            $table->timestamps();
        });

        Schema::create('client_contacts', function ($table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function test_company_currency_relation_ignores_current_tenant_scope(): void
    {
        DB::table('companies')->insert([
            ['id' => 1, 'company_name' => 'Tenant 1', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'company_name' => 'Tenant 2', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('currencies')->insert([
            ['id' => 11, 'company_id' => 1, 'currency_name' => 'US Dollar', 'currency_symbol' => '$', 'currency_code' => 'USD', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'company_id' => 2, 'currency_name' => 'Euro', 'currency_symbol' => '€', 'currency_code' => 'EUR', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('companies')->where('id', 1)->update(['currency_id' => 11]);
        DB::table('companies')->where('id', 2)->update(['currency_id' => 22]);

        auth()->setUser(new TitanFakeUser(99, 1, ['admin'], ['view_companies' => 'all']));
        session(['company' => Company::withoutGlobalScopes()->find(1)]);

        $company = Company::withoutGlobalScopes()->findOrFail(2);

        $this->assertNotNull($company->currency);
        $this->assertSame(22, $company->currency->id);
        $this->assertSame('EUR', $company->currency->currency_code);
    }

    public function test_first_active_admin_excludes_superadmin_flagged_users(): void
    {
        DB::table('companies')->insert([
            'id' => 2,
            'company_name' => 'Tenant 2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('roles')->insert([
            ['id' => 201, 'company_id' => 2, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 202, 'company_id' => 2, 'name' => 'employee', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('users')->insert([
            ['id' => 301, 'company_id' => 2, 'name' => 'Wrong Super', 'email' => 'wrong@example.test', 'status' => 'active', 'is_superadmin' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 302, 'company_id' => 2, 'name' => 'Tenant Admin', 'email' => 'admin@example.test', 'status' => 'active', 'is_superadmin' => 0, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('role_user')->insert([
            ['user_id' => 301, 'role_id' => 201],
            ['user_id' => 302, 'role_id' => 201],
        ]);

        $company = Company::withoutGlobalScopes()->findOrFail(2);
        $admin = Company::firstActiveAdmin($company);

        $this->assertNotNull($admin);
        $this->assertSame(302, $admin->id);
    }

    public function test_company_user_relation_prefers_tenant_admin_and_ignores_superadmin(): void
    {
        DB::table('companies')->insert([
            'id' => 3,
            'company_name' => 'Tenant 3',
            'currency_id' => 31,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('currencies')->insert([
            'id' => 31,
            'company_id' => 3,
            'currency_name' => 'US Dollar',
            'currency_symbol' => '$',
            'currency_code' => 'USD',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('roles')->insert([
            ['id' => 401, 'company_id' => 3, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 402, 'company_id' => 3, 'name' => 'employee', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('users')->insert([
            [
                'id' => 501,
                'company_id' => 3,
                'name' => 'Wrong Super',
                'email' => 'wrong-super@example.test',
                'status' => 'active',
                'is_superadmin' => 1,
                'locale' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 502,
                'company_id' => 3,
                'name' => 'Tenant Admin',
                'email' => 'tenant-admin@example.test',
                'status' => 'active',
                'is_superadmin' => 0,
                'locale' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('role_user')->insert([
            ['user_id' => 501, 'role_id' => 401],
            ['user_id' => 502, 'role_id' => 401],
        ]);

        $company = Company::withoutGlobalScopes()->findOrFail(3);
        $admin = $company->user;

        $this->assertNotNull($admin);
        $this->assertSame(502, $admin->id);
    }

    public function test_first_active_admin_returns_null_when_no_company_admin_exists(): void
    {
        DB::table('companies')->insert([
            'id' => 4,
            'company_name' => 'Tenant 4',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('roles')->insert([
            'id' => 601,
            'company_id' => 4,
            'name' => 'employee',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 701,
            'company_id' => 4,
            'name' => 'Only Employee',
            'email' => 'employee@example.test',
            'status' => 'active',
            'is_superadmin' => 0,
            'locale' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('role_user')->insert([
            'user_id' => 701,
            'role_id' => 601,
        ]);

        $company = Company::withoutGlobalScopes()->findOrFail(4);
        $this->assertNull(Company::firstActiveAdmin($company));
    }
}
