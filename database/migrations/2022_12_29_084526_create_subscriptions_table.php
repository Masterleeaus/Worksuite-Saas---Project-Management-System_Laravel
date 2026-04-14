<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'stripe_id')) {
                $table->string('stripe_id')->nullable()->index();
            }
            if (!Schema::hasColumn('users', 'pm_type')) {
                $table->string('pm_type')->nullable();
            }
            if (!Schema::hasColumn('users', 'pm_last_four')) {
                $table->string('pm_last_four', 4)->nullable();
            }
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable();
            }
        });

        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->integer('company_id')->unsigned()->nullable();
                $table->foreign('company_id')
                    ->references('id')
                    ->on('companies')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

                $table->integer('user_id')->unsigned()->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

                $table->string('name');
                $table->string('stripe_id')->unique();
                $table->string('stripe_status');
                $table->string('stripe_price')->nullable();
                $table->integer('quantity')->nullable();
                $table->timestamp('trial_ends_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'stripe_status']);
            });

            Schema::create('subscription_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscription_id');
                $table->string('stripe_id')->unique();
                $table->string('stripe_product');
                $table->string('stripe_price');
                $table->integer('quantity')->nullable();
                $table->timestamps();

                $table->unique(['subscription_id', 'stripe_price']);
            });
        }

        if (!Schema::hasColumn('subscriptions', 'company_id')) {

            Schema::table('subscriptions', function (Blueprint $table) {
                if (!Schema::hasColumn('subscriptions', 'stripe_price')) {
                    $table->string('stripe_price')->after('stripe_id');
                }
                $table->integer('company_id')->unsigned()->nullable()->after('id');
                $table->foreign('company_id')
                    ->references('id')
                    ->on('companies')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }

        Schema::table('subscription_items', function (Blueprint $table) {
            if (!Schema::hasColumn('subscription_items', 'stripe_price')) {
                $table->string('stripe_price');
            }
            if (Schema::hasColumn('subscription_items', 'stripe_plan') && !Schema::hasColumn('subscription_items', 'stripe_product')) {
                $table->renameColumn('stripe_plan', 'stripe_product');
            }
        });

        if (!Schema::hasColumn('subscriptions', 'stripe_price')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->string('stripe_price')->after('stripe_id');
            });
        }


    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('subscription_items');
    }

};
