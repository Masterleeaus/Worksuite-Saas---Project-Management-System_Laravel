# PASS 82 Site-Dependency Extraction Report

## Files added from Worksuite-Site.zip into installer package
- app/Events/ModuleStatusChanged.php
- app/Helper/Reply.php
- app/Helper/start.php
- app/Http/Controllers/Controller.php
- app/Http/Controllers/AccountBaseController.php
- app/Models/BaseModel.php
- app/Models/GlobalSetting.php
- app/Models/Module.php
- app/Scopes/CompanyScope.php
- app/Scopes/SuperAdminModuleScope.php
- app/Traits/HasCompany.php
- app/Traits/UniversalSearchTrait.php

## Why these were added
- CustomModuleController and ModuleSettingController in the installer overlay directly rely on Worksuite base controller, reply helper, module status event, global settings, module model, helper functions, and model base traits/scopes.
- These files exist in Worksuite-Site.zip and are the minimum high-value site dependencies to include without merging the full site.

## Remaining host-app dependencies after this pass
- app/Enums/MaritalStatus.php ← app/Observers/CompanyObserver.php
- app/Events/NewCompanyCreatedEvent.php ← app/Console/Commands/FixUpgradeCompanyCommand.php, app/Observers/CompanyObserver.php
- app/Helper/Files.php ← app/Helper/start.php, app/Observers/CompanyObserver.php
- app/Http/Controllers/AppSettingController.php ← app/Observers/CompanyObserver.php
- app/Http/Controllers/CurrencySettingController.php ← app/Observers/CompanyObserver.php
- app/Http/Controllers/RolePermissionController.php ← app/Observers/CompanyObserver.php
- app/Models/AttendanceSetting.php ← app/Console/Commands/FixUpgradeCompanyCommand.php, app/Helper/start.php, app/Observers/CompanyObserver.php
- app/Models/Company.php ← app/Console/Commands/FixUpgradeCompanyCommand.php, app/Helper/start.php, app/Observers/CompanyObserver.php, app/Traits/HasCompany.php
- app/Models/Currency.php ← app/Helper/start.php, app/Observers/CompanyObserver.php
- app/Models/CustomField.php ← app/Console/Commands/FixUpgradeCompanyCommand.php
- app/Models/CustomFieldGroup.php ← app/Console/Commands/FixUpgradeCompanyCommand.php, app/Observers/CompanyObserver.php
- app/Models/CustomLinkSetting.php ← app/Helper/start.php
- app/Models/DashboardWidget.php ← app/Console/Commands/FixUpgradeCompanyCommand.php, app/Observers/CompanyObserver.php
- app/Models/DiscussionCategory.php ← app/Observers/CompanyObserver.php
- app/Models/EmailNotificationSetting.php ← app/Observers/CompanyObserver.php
- app/Models/EmployeeShift.php ← app/Console/Commands/FixUpgradeCompanyCommand.php, app/Observers/CompanyObserver.php
- app/Models/GdprSetting.php ← app/Helper/start.php
- app/Models/GoogleCalendarModule.php ← app/Observers/CompanyObserver.php
- app/Models/InvoiceSetting.php ← app/Helper/start.php, app/Observers/CompanyObserver.php
- app/Models/LanguageSetting.php ← app/Console/Commands/FixUpgradeCompanyCommand.php, app/Helper/start.php
- app/Models/LeadCustomForm.php ← app/Observers/CompanyObserver.php
- app/Models/LeadPipeline.php ← app/Observers/CompanyObserver.php
- app/Models/LeadSource.php ← app/Observers/CompanyObserver.php
- app/Models/LeadStatus.php ← app/Observers/CompanyObserver.php
- app/Models/LeaveType.php ← app/Observers/CompanyObserver.php
- app/Models/LogTimeFor.php ← app/Helper/start.php, app/Observers/CompanyObserver.php
- app/Models/MessageSetting.php ← app/Observers/CompanyObserver.php
- app/Models/Notification.php ← app/Observers/CompanyObserver.php
- app/Models/PackageUpdateNotify.php ← app/Observers/CompanyObserver.php, app/Observers/SuperAdmin/PackageObserver.php
- app/Models/PaymentGatewayCredentials.php ← app/Console/Commands/FixUpgradeCompanyCommand.php
- app/Models/Permission.php ← app/Helper/start.php, app/Observers/CompanyObserver.php
- app/Models/PermissionRole.php ← app/Observers/CompanyObserver.php
- app/Models/PipelineStage.php ← app/Observers/CompanyObserver.php
- app/Models/ProjectActivity.php ← app/Http/Controllers/AccountBaseController.php
- app/Models/ProjectSetting.php ← app/Observers/CompanyObserver.php
- app/Models/ProjectStatusSetting.php ← app/Observers/CompanyObserver.php
- app/Models/ProjectTimeLog.php ← app/Http/Controllers/AccountBaseController.php
- app/Models/PusherSetting.php ← app/Console/Commands/FixUpgradeCompanyCommand.php
- app/Models/QuickBooksSetting.php ← app/Helper/start.php, app/Observers/CompanyObserver.php
- app/Models/Role.php ← app/Observers/CompanyObserver.php
- app/Models/SlackSetting.php ← app/Console/Commands/FixUpgradeCompanyCommand.php, app/Observers/CompanyObserver.php
- app/Models/SocialAuthSetting.php ← app/Helper/start.php
- app/Models/StorageSetting.php ← app/Helper/start.php
- app/Models/SuperAdmin/GlobalCurrency.php ← app/Helper/start.php, app/Models/GlobalSetting.php, app/Observers/CompanyObserver.php
- app/Models/SuperAdmin/GlobalInvoice.php ← app/Observers/CompanyObserver.php
- app/Models/SuperAdmin/GlobalInvoiceSetting.php ← app/Helper/start.php
- app/Models/SuperAdmin/GlobalPaymentGatewayCredentials.php ← app/Console/Commands/FixUpgradeCompanyCommand.php
- app/Models/SuperAdmin/GlobalSubscription.php ← app/Observers/CompanyObserver.php
- app/Models/SuperAdmin/OfflinePlanChange.php ← app/Http/Controllers/AccountBaseController.php
- app/Models/SuperAdmin/Package.php ← app/Observers/CompanyObserver.php, app/Observers/SuperAdmin/PackageObserver.php
- app/Models/SuperAdmin/PackageSetting.php ← app/Observers/CompanyObserver.php
- app/Models/SuperAdmin/SupportTicket.php ← app/Http/Controllers/AccountBaseController.php
- app/Models/TaskHistory.php ← app/Http/Controllers/AccountBaseController.php
- app/Models/TaskboardColumn.php ← app/Observers/CompanyObserver.php
- app/Models/ThemeSetting.php ← app/Helper/start.php, app/Observers/CompanyObserver.php
- app/Models/TicketChannel.php ← app/Observers/CompanyObserver.php
- app/Models/TicketCustomForm.php ← app/Observers/CompanyObserver.php
- app/Models/TicketEmailSetting.php ← app/Observers/CompanyObserver.php
- app/Models/TicketSettingForAgents.php ← app/Observers/CompanyObserver.php
- app/Models/TicketType.php ← app/Observers/CompanyObserver.php
- app/Models/UnitType.php ← app/Observers/CompanyObserver.php
- app/Models/UniversalSearch.php ← app/Traits/UniversalSearchTrait.php
- app/Models/User.php ← app/Helper/start.php, app/Http/Controllers/AccountBaseController.php, app/Observers/CompanyObserver.php
- app/Models/UserActivity.php ← app/Http/Controllers/AccountBaseController.php
- app/Models/UserAuth.php ← app/Http/Controllers/AccountBaseController.php, app/Observers/CompanyObserver.php
- app/Models/UserChat.php ← app/Http/Controllers/AccountBaseController.php
- app/Models/UserPermission.php ← app/Helper/start.php
- app/Scopes/ActiveScope.php ← app/Console/Commands/FixUpgradeCompanyCommand.php, app/Observers/CompanyObserver.php
- app/Traits/HasMaskImage.php ← app/Models/GlobalSetting.php
- app/Traits/StoreHeaders.php ← app/Observers/CompanyObserver.php

## Route-layer imports intentionally still point at host Worksuite app
- app/Http/Controllers/AppSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/AttendanceSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/BusinessAddressController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/ContractSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/CurrencySettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/CustomFieldController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/CustomLinkSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/DatabaseBackupSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/EmployeeShiftController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/GoogleAuthController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/GoogleCalendarSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/InvoiceSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/LanguageSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/LeadAgentSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/LeadPipelineSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/LeadSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/LeadSourceSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/LeadStageSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/LeaveSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/LeaveTypeController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/MessageSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/NotificationController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/NotificationSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/OfflinePaymentSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/PaymentGatewayCredentialController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/ProfileController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/ProfileSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/ProjectSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/PushNotificationController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/PusherSettingsController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/QuickbookSettingsController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/RolePermissionController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/SecuritySettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/SettingsController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/ShiftRotationController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/SignUpSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/SlackSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/SmtpSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/SocialAuthSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/StorageSettingController.php ← routes/Titan/custom-module-doctor.routes.php, routes/web-settings.php
- app/Http/Controllers/SuperAdmin/AuthorizeController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/AuthorizeWebhookController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/BillingController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/CompanyController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/CustomFieldController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/DashboardController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FaqCategoryController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FaqController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/ClientSettingController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/FaqSettingController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/FeatureSettingController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/FeatureTranslationSettingController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/FooterSettingController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/FrontMenuController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/FrontSettingController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/FrontWidgetController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/SeoDetailController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/SignUpController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/SocialLinkSettingController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php
- app/Http/Controllers/SuperAdmin/FrontSetting/TestimonialSettingController.php ← routes/SuperAdmin/web.php, routes/Titan/superadmin-marketplace.routes.php

## ZIP cross-check performed
### Worksuite-Site.zip
- Worksuite-Saas---Project-Management-System_Laravel-main/app/Events/ModuleStatusChanged.php
- Worksuite-Saas---Project-Management-System_Laravel-main/app/Helper/Reply.php
- Worksuite-Saas---Project-Management-System_Laravel-main/app/Http/Controllers/AccountBaseController.php
- Worksuite-Saas---Project-Management-System_Laravel-main/app/Http/Controllers/CustomModuleController.php
- Worksuite-Saas---Project-Management-System_Laravel-main/app/Http/Controllers/ModuleSettingController.php
- Worksuite-Saas---Project-Management-System_Laravel-main/app/Models/DiscussionReply.php
- Worksuite-Saas---Project-Management-System_Laravel-main/app/Models/SuperAdmin/SupportTicketReply.php
- Worksuite-Saas---Project-Management-System_Laravel-main/app/Models/TicketReply.php
- Worksuite-Saas---Project-Management-System_Laravel-main/app/Notifications/MailTicketReply.php
- Worksuite-Saas---Project-Management-System_Laravel-main/app/Notifications/NewDiscussionReply.php
- ... 4 more relevant entries

### Worksuite_Custom_Installer_Worksuite_Module_Only_PASS81.zip
- app/Http/Controllers/CustomModuleController.php
- app/Http/Controllers/ModuleSettingController.php
- resources/views/custom-modules/install.blade.php
- resources/views/module-settings/index.blade.php

### Worksuite_Custom_Installer_Worksuite_Only_PASS80.zip
- app/Http/Controllers/CustomModuleController.php
- app/Http/Controllers/ModuleSettingController.php
- resources/views/custom-modules/install.blade.php
- resources/views/module-settings/index.blade.php

### Worksuite_Custom_Installer_with_MagicAI_From_CustomExtensions.zip
- app/Http/Controllers/CustomModuleController.php
- app/Http/Controllers/ModuleSettingController.php
- resources/views/custom-modules/install.blade.php
- resources/views/module-settings/index.blade.php

### modules.zip
- Accountings/module.json
- Affiliate/module.json
- Aitools/module.json
- Asset/module.json
- BidModule/module.json
- Biolinks/module.json
- Biometric/module.json
- Blogs/module.json
- BookingModule/module.json
- BusinessSettingsModule/module.json
- ... 15 more relevant entries
