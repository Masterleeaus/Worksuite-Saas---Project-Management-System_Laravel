<?php

namespace Modules\CustomerFeedback\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CustomerFeedback\Entities\FeedbackTicket;
use Modules\CustomerFeedback\Entities\FeedbackReply;
use Modules\CustomerFeedback\Observers\BookingObserver;
use Modules\CustomerFeedback\Observers\FeedbackTicketObserver;
use Modules\CustomerFeedback\Observers\FeedbackReplyObserver;

class CustomerFeedbackServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    public function boot(): void
    {
        // Register observers
        FeedbackTicket::observe(FeedbackTicketObserver::class);
        FeedbackReply::observe(FeedbackReplyObserver::class);

        // Register booking observer only when BookingModule is installed
        if (class_exists(\Modules\BookingModule\Entities\Booking::class)) {
            \Modules\BookingModule\Entities\Booking::observe(BookingObserver::class);
        }

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'customer-feedback');

        // Load translations
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'customer-feedback');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../Database/Migrations' => database_path('migrations'),
        ], 'customer-feedback-migrations');

        // Publish config
        $this->publishes([
            __DIR__ . '/../Config' => config_path('customer-feedback'),
        ], 'customer-feedback-config');
    }
}
