<?php

namespace Tests\Feature\FSM;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\Feature\Titan\Support\TitanFakeUser;
use Tests\TestCase;

class FsmTargetModulePromotionBootstrapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');
        DB::purge('sqlite');
        DB::reconnect('sqlite');
        Schema::dropAllTables();

        Schema::create('users', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('fsm_orders', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('task_id')->nullable();
            $table->timestamps();
        });

        Schema::create('fsm_sizes', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->string('unit_of_measure')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_order_size')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('sessions', function ($table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->mediumText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('client_contacts', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'id' => 11,
            'company_id' => 1,
            'name' => 'FSM Tenant User',
            'email' => 'fsm-user@example.test',
            'password' => 'x',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('fsm_orders')->insert([
            'id' => 101,
            'company_id' => 1,
            'name' => 'Order A',
            'project_id' => null,
            'task_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withoutMiddleware();
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->actingAs(new TitanFakeUser(11, 1, ['admin']));
    }

    public function test_target_routes_bootstrap_for_promoted_modules(): void
    {
        $this->assertTrue(Route::has('fsmproject.index'));
        $this->assertTrue(Route::has('fsmproject.store'));
        $this->assertTrue(Route::has('fsmsize.index'));
        $this->assertTrue(Route::has('fsmsize.store'));
        $this->assertTrue(Route::has('api.fsmproject.orders.link'));
        $this->assertTrue(Route::has('api.fsmsize.index'));
    }

    public function test_fsmsize_web_render_and_save_flow(): void
    {
        $controller = app(\Modules\FSMSize\Http\Controllers\FsmSizeController::class);
        $indexResponse = $controller->index(Request::create(route('fsmsize.index'), 'GET'));
        $this->assertInstanceOf(View::class, $indexResponse);

        $storeResponse = $controller->store(Request::create(route('fsmsize.store'), 'POST', [
            'name' => 'Small Apartment',
            'unit_of_measure' => 'sqm',
            'is_order_size' => 1,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $storeResponse);
        $this->assertSame(route('fsmsize.index'), $storeResponse->getTargetUrl());

        $this->assertDatabaseHas('fsm_sizes', [
            'company_id' => 1,
            'name' => 'Small Apartment',
        ]);
    }

    public function test_fsmproject_web_render_and_save_flow(): void
    {
        $controller = app(\Modules\FSMProject\Http\Controllers\FsmProjectController::class);
        $createResponse = $controller->create();
        $this->assertInstanceOf(View::class, $createResponse);

        $storeResponse = $controller->store(Request::create(route('fsmproject.store'), 'POST', [
            'order_id' => 101,
            'project_id' => 555,
            'task_id' => 777,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $storeResponse);
        $this->assertSame(route('fsmproject.index'), $storeResponse->getTargetUrl());

        $this->assertDatabaseHas('fsm_orders', [
            'id' => 101,
            'company_id' => 1,
            'project_id' => 555,
            'task_id' => 777,
        ]);
    }
}
