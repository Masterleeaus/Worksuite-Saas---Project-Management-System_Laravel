<?php

namespace Modules\Sms\Providers;

use App\Events\AttendanceReminderEvent;
use App\Events\AutoFollowUpReminderEvent;
use App\Events\AutoTaskReminderEvent;
use App\Events\ContractSignedEvent;
use App\Events\EstimateDeclinedEvent;
use App\Events\EventInviteEvent;
use App\Events\EventReminderEvent;
use App\Events\FileUploadEvent;
use App\Events\InvoicePaymentReceivedEvent;
use App\Events\InvoiceReminderEvent;
use App\Events\InvoiceUpdatedEvent;
use App\Events\LeadEvent;
use App\Events\LeaveEvent;
use App\Events\NewCompanyCreatedEvent;
use App\Events\NewExpenseEvent;
use App\Events\NewExpenseRecurringEvent;
use App\Events\NewInvoiceEvent;
use App\Events\NewInvoiceRecurringEvent;
use App\Events\NewNoticeEvent;
use App\Events\NewOrderEvent;
use App\Events\NewPaymentEvent;
use App\Events\NewProductPurchaseEvent;
use App\Events\NewProjectEvent;
use App\Events\NewProjectMemberEvent;
use App\Events\NewProposalEvent;
use App\Events\NewUserEvent;
use App\Events\NewUserRegistrationViaInviteEvent;
use App\Events\OrderUpdatedEvent;
use App\Events\PaymentReminderEvent;
use App\Events\ProjectReminderEvent;
use App\Events\RemovalRequestAdminEvent;
use App\Events\RemovalRequestAdminLeadEvent;
use App\Events\RemovalRequestApproveRejectEvent;
use App\Events\RemovalRequestApprovedRejectLeadEvent;
use App\Events\RemovalRequestApprovedRejectUserEvent;
use App\Events\SubTaskCompletedEvent;
use App\Events\TaskCommentEvent;
use App\Events\TaskEvent;
use App\Events\TaskNoteEvent;
use App\Events\TaskNoteMentionEvent;
use App\Events\TaskReminderEvent;
use App\Events\TicketEvent;
use App\Events\TicketReplyEvent;
use App\Events\TicketRequesterEvent;
use App\Events\TwoFactorCodeEvent;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\Sms\Console\ActivateModuleCommand;
use Modules\Sms\Http\Traits\SmsSettingTrait;
use Modules\Sms\Listeners\AttendanceReminderListener;
use Modules\Sms\Listeners\AutoFollowUpReminderListener;
use Modules\Sms\Listeners\AutoTaskReminderListener;
use Modules\Sms\Listeners\CompanyCreatedListener;
use Modules\Sms\Listeners\ContractSignedListener;
use Modules\Sms\Listeners\EstimateDeclinedListener;
use Modules\Sms\Listeners\EventReminderListener;
use Modules\Sms\Listeners\FileUploadListener;
use Modules\Sms\Listeners\InvoicePaymentReceivedListener;
use Modules\Sms\Listeners\InvoiceReminderListener;
use Modules\Sms\Listeners\InvoiceUpdatedListener;
use Modules\Sms\Listeners\LeadListener;
use Modules\Sms\Listeners\NewExpenseRecurringListener;
use Modules\Sms\Listeners\NewInvoiceRecurringListener;
use Modules\Sms\Listeners\NewOrderListener;
use Modules\Sms\Listeners\NewPaymentListener;
use Modules\Sms\Listeners\NewProductPurchaseListener;
use Modules\Sms\Listeners\NewProjectListener;
use Modules\Sms\Listeners\NewProposalListener;
use Modules\Sms\Listeners\NewUserListener;
use Modules\Sms\Listeners\NewUserRegistrationViaInviteListener;
use Modules\Sms\Listeners\OrderUpdatedListener;
use Modules\Sms\Listeners\PaymentReminderListener;
use Modules\Sms\Listeners\ProjectReminderListener;
use Modules\Sms\Listeners\RemovalRequestAdminLeadListener;
use Modules\Sms\Listeners\RemovalRequestAdminListener;
use Modules\Sms\Listeners\RemovalRequestApprovedRejectLeadListener;
use Modules\Sms\Listeners\RemovalRequestApprovedRejectListener;
use Modules\Sms\Listeners\RemovalRequestApprovedRejectUserListener;
use Modules\Sms\Listeners\SmsEventInviteListener;
use Modules\Sms\Listeners\SmsInvoiceListener;
use Modules\Sms\Listeners\SmsLeaveListener;
use Modules\Sms\Listeners\SmsNewExpenseListener;
use Modules\Sms\Listeners\SmsNewNoticeListener;
use Modules\Sms\Listeners\SmsNewProjectMemberListener;
use Modules\Sms\Listeners\SmsTaskListener;
use Modules\Sms\Listeners\SmsTicketListener;
use Modules\Sms\Listeners\SubTaskCompletedListener;
use Modules\Sms\Listeners\TaskCommentListener;
use Modules\Sms\Listeners\TaskNoteListener;
use Modules\Sms\Listeners\TaskNoteMentionListener;
use Modules\Sms\Listeners\TaskReminderListener;
use Modules\Sms\Listeners\TicketReplyListener;
use Modules\Sms\Listeners\TicketRequesterListener;
use Modules\Sms\Listeners\TwoFactorCodeListener;

class SmsServiceProvider extends ServiceProvider
{
    use SmsSettingTrait;

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerCommands();

        // ---------------------------------------------------------------
        // Event listeners (Worksuite notifications)
        // ---------------------------------------------------------------
        Event::listen(AttendanceReminderEvent::class,              AttendanceReminderListener::class);
        Event::listen(AutoFollowUpReminderEvent::class,            AutoFollowUpReminderListener::class);
        Event::listen(AutoTaskReminderEvent::class,                AutoTaskReminderListener::class);
        Event::listen(ContractSignedEvent::class,                  ContractSignedListener::class);
        Event::listen(EstimateDeclinedEvent::class,                EstimateDeclinedListener::class);
        Event::listen(EventInviteEvent::class,                     SmsEventInviteListener::class);
        Event::listen(EventReminderEvent::class,                   EventReminderListener::class);
        Event::listen(FileUploadEvent::class,                      FileUploadListener::class);
        Event::listen(InvoicePaymentReceivedEvent::class,          InvoicePaymentReceivedListener::class);
        Event::listen(InvoiceReminderEvent::class,                 InvoiceReminderListener::class);
        Event::listen(InvoiceUpdatedEvent::class,                  InvoiceUpdatedListener::class);
        Event::listen(LeadEvent::class,                            LeadListener::class);
        Event::listen(NewInvoiceRecurringEvent::class,             NewInvoiceRecurringListener::class);
        Event::listen(NewExpenseRecurringEvent::class,             NewExpenseRecurringListener::class);
        Event::listen(NewOrderEvent::class,                        NewOrderListener::class);
        Event::listen(NewPaymentEvent::class,                      NewPaymentListener::class);
        Event::listen(NewProductPurchaseEvent::class,              NewProductPurchaseListener::class);
        Event::listen(NewProjectEvent::class,                      NewProjectListener::class);
        Event::listen(NewProposalEvent::class,                     NewProposalListener::class);
        Event::listen(NewUserEvent::class,                         NewUserListener::class);
        Event::listen(NewUserRegistrationViaInviteEvent::class,    NewUserRegistrationViaInviteListener::class);
        Event::listen(OrderUpdatedEvent::class,                    OrderUpdatedListener::class);
        Event::listen(PaymentReminderEvent::class,                 PaymentReminderListener::class);
        Event::listen(ProjectReminderEvent::class,                 ProjectReminderListener::class);
        Event::listen(TicketReplyEvent::class,                     TicketReplyListener::class);
        Event::listen(TicketRequesterEvent::class,                 TicketRequesterListener::class);
        Event::listen(TaskEvent::class,                            SmsTaskListener::class);
        Event::listen(NewInvoiceEvent::class,                      SmsInvoiceListener::class);
        Event::listen(LeaveEvent::class,                           SmsLeaveListener::class);
        Event::listen(NewExpenseEvent::class,                      SmsNewExpenseListener::class);
        Event::listen(NewProjectMemberEvent::class,                SmsNewProjectMemberListener::class);
        Event::listen(NewNoticeEvent::class,                       SmsNewNoticeListener::class);
        Event::listen(RemovalRequestAdminLeadEvent::class,         RemovalRequestAdminLeadListener::class);
        Event::listen(RemovalRequestAdminEvent::class,             RemovalRequestAdminListener::class);
        Event::listen(RemovalRequestApproveRejectEvent::class,     RemovalRequestApprovedRejectListener::class);
        Event::listen(RemovalRequestApprovedRejectLeadEvent::class,RemovalRequestApprovedRejectLeadListener::class);
        Event::listen(RemovalRequestApprovedRejectUserEvent::class,RemovalRequestApprovedRejectUserListener::class);
        Event::listen(SubTaskCompletedEvent::class,                SubTaskCompletedListener::class);
        Event::listen(TaskCommentEvent::class,                     TaskCommentListener::class);
        Event::listen(TaskNoteEvent::class,                        TaskNoteListener::class);
        Event::listen(TaskReminderEvent::class,                    TaskReminderListener::class);
        Event::listen(TicketEvent::class,                          SmsTicketListener::class);
        Event::listen(NewCompanyCreatedEvent::class,               CompanyCreatedListener::class);
        Event::listen(TwoFactorCodeEvent::class,                   TwoFactorCodeListener::class);
        Event::listen(TaskNoteMentionEvent::class,                 TaskNoteMentionListener::class);

        // ---------------------------------------------------------------
        // Config bootstrap: load SmsSetting into Laravel config at boot
        // ---------------------------------------------------------------
        try {
            if (Schema::hasTable('sms_settings')) {
                $this->setConfig();
            }
        } catch (\Exception $e) {
            // Fail silently during migrations/installs
        }

        // ---------------------------------------------------------------
        // SMSModule gateway lib: auto-require if present (backward compat)
        // Ensures SMS_gateway helper class is available for legacy callers.
        // ---------------------------------------------------------------
        $gatewayLib = __DIR__ . '/../Lib/SMS_gateway.php';
        if (file_exists($gatewayLib)) {
            require_once $gatewayLib;
        }

        // ---------------------------------------------------------------
        // TitanZero capability registry
        // ---------------------------------------------------------------
        if (class_exists(\Modules\TitanZero\Services\CapabilityRegistry::class)) {
            \Modules\TitanZero\Services\CapabilityRegistry::registerModuleFromConfig('Sms');
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config files.
     */
    protected function registerConfig(): void
    {
        // Primary module config
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('sms.php'),
        ], 'config');
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'sms');

        // XSS ignore config
        $this->mergeConfigFrom(
            module_path('sms', 'Config/xss_ignore.php'),
            'sms::xss_ignore'
        );

        // Automation/AI manifest (merged from SMSModule)
        $this->mergeConfigFrom(__DIR__ . '/../Config/automation.php', 'sms.automation');
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath   = resource_path('views/modules/sms');
        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([$sourcePath => $viewPath], 'views');

        $this->loadViewsFrom(
            array_merge(
                array_map(fn ($path) => $path . '/modules/sms', \Config::get('view.paths')),
                [$sourcePath]
            ),
            'sms'
        );
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/sms');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'sms');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'sms');
        }
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    /**
     * Register artisan commands.
     */
    private function registerCommands(): void
    {
        $this->commands([ActivateModuleCommand::class]);
    }
}
