<?php

namespace Tests\Feature\Titan;

use App\Filament\Resources\InvoiceResource;
use App\Policies\LeadPolicy;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Titan\Support\TitanFakeUser;
use Tests\TestCase;

class TitanTierOneResourcesTest extends TestCase
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
            $table->timestamps();
        });

        Schema::create('invoices', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('invoice_number')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->double('sub_total')->default(0);
            $table->double('discount')->default(0);
            $table->string('discount_type')->default('fixed');
            $table->double('total')->default(0);
            $table->string('status')->default('unpaid');
            $table->timestamps();
        });
    }

    public function test_invoice_resource_query_is_tenant_scoped(): void
    {
        DB::table('invoices')->insert([
            ['id' => 1, 'company_id' => 1, 'invoice_number' => 'INV-1', 'added_by' => 100, 'issue_date' => now()->toDateString(), 'due_date' => now()->toDateString(), 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'company_id' => 2, 'invoice_number' => 'INV-2', 'added_by' => 200, 'issue_date' => now()->toDateString(), 'due_date' => now()->toDateString(), 'created_at' => now(), 'updated_at' => now()],
        ]);

        auth()->setUser(new TitanFakeUser(100, 1, ['admin'], [
            'titan_access' => 'all',
            'view_invoices' => 'all',
        ]));

        $ids = InvoiceResource::getEloquentQuery()->pluck('invoices.id')->all();

        $this->assertContains(1, $ids);
        $this->assertNotContains(2, $ids);
    }

    public function test_lead_policy_matches_role_matrix(): void
    {
        $policy = new LeadPolicy();

        $superadmin = new TitanFakeUser(1, null, ['superadmin'], ['view_lead' => false]);
        $superadmin->is_superadmin = 1;
        $this->assertTrue($policy->viewAny($superadmin));

        $admin = new TitanFakeUser(2, 1, ['admin'], ['view_lead' => false]);
        $this->assertTrue($policy->viewAny($admin));

        $employee = new TitanFakeUser(3, 1, ['employee'], ['view_lead' => 'none']);
        $this->assertFalse($policy->viewAny($employee));

        $employeeWithScope = new TitanFakeUser(4, 1, ['employee'], ['view_lead' => 'added']);
        $this->assertTrue($policy->viewAny($employeeWithScope));

        $client = new TitanFakeUser(5, 1, ['client'], ['view_lead' => 'all']);
        $this->assertFalse($policy->viewAny($client));
    }
}
