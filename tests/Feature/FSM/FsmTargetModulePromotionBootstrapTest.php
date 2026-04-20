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

        Schema::create('fsm_frequencies', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->unsignedInteger('interval')->default(1);
            $table->string('interval_type', 20)->default('weekly');
            $table->boolean('is_exclusive')->default(false);
            $table->boolean('use_bymonthday')->default(false);
            $table->unsignedTinyInteger('month_day')->nullable();
            $table->boolean('use_byweekday')->default(false);
            $table->boolean('weekday_mo')->default(false);
            $table->boolean('weekday_tu')->default(false);
            $table->boolean('weekday_we')->default(false);
            $table->boolean('weekday_th')->default(false);
            $table->boolean('weekday_fr')->default(false);
            $table->boolean('weekday_sa')->default(false);
            $table->boolean('weekday_su')->default(false);
            $table->boolean('use_bymonth')->default(false);
            $table->boolean('month_jan')->default(false);
            $table->boolean('month_feb')->default(false);
            $table->boolean('month_mar')->default(false);
            $table->boolean('month_apr')->default(false);
            $table->boolean('month_may')->default(false);
            $table->boolean('month_jun')->default(false);
            $table->boolean('month_jul')->default(false);
            $table->boolean('month_aug')->default(false);
            $table->boolean('month_sep')->default(false);
            $table->boolean('month_oct')->default(false);
            $table->boolean('month_nov')->default(false);
            $table->boolean('month_dec')->default(false);
            $table->boolean('use_setpos')->default(false);
            $table->integer('set_pos')->nullable();
            $table->timestamps();
        });

        Schema::create('fsm_skill_types', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name', 128);
            $table->text('description')->nullable();
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
        $this->assertTrue(Route::has('fsmproject.update'));
        $this->assertTrue(Route::has('fsmproject.destroy'));
        $this->assertTrue(Route::has('fsmsize.update'));
        $this->assertTrue(Route::has('fsmsize.destroy'));
        $this->assertTrue(Route::has('fsmrecurring.frequencies.index'));
        $this->assertTrue(Route::has('fsmskill.skill-types.index'));
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

    public function test_fsmrecurring_frequency_web_render_and_save_flow(): void
    {
        $controller = app(\Modules\FSMRecurring\Http\Controllers\FrequencyController::class);
        $indexResponse = $controller->index();
        $this->assertInstanceOf(View::class, $indexResponse);

        $storeResponse = $controller->store(Request::create(route('fsmrecurring.frequencies.store'), 'POST', [
            'name' => 'Weekly',
            'interval' => 1,
            'interval_type' => 'weekly',
            'active' => 1,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $storeResponse);
        $this->assertSame(route('fsmrecurring.frequencies.index'), $storeResponse->getTargetUrl());

        $this->assertDatabaseHas('fsm_frequencies', [
            'company_id' => 1,
            'name' => 'Weekly',
        ]);
    }

    public function test_fsmskill_type_web_render_and_save_flow(): void
    {
        $controller = app(\Modules\FSMSkill\Http\Controllers\SkillTypeController::class);
        $indexResponse = $controller->index();
        $this->assertInstanceOf(View::class, $indexResponse);

        $storeResponse = $controller->store(Request::create(route('fsmskill.skill-types.store'), 'POST', [
            'name' => 'Cleaning',
            'active' => 1,
        ]));

        $this->assertInstanceOf(RedirectResponse::class, $storeResponse);
        $this->assertSame(route('fsmskill.skill-types.index'), $storeResponse->getTargetUrl());

        $this->assertDatabaseHas('fsm_skill_types', [
            'company_id' => 1,
            'name' => 'Cleaning',
        ]);
    }
}
