# Module Runbooks and Test Acceptance Criteria

_Generated: 2026-04-20 14:56:11 UTC_

Each section defines executable run steps and acceptance gates tied to actual module source files.

## Accountings

- **Alias:** `accountings`
- **Activation status map key:** `Accountings` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Accountings/module.json`
- **Route files:** 3
- **Migrations:** 25
- **Tests:** 3

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Accountings
# 2) run module migrations
php artisan module:migrate Accountings
# 3) run module tests
php artisan test Modules/Accountings/Tests/Feature/ChartOfAccountsTest.php Modules/Accountings/Tests/Feature/XeroSyncTest.php Modules/Accountings/Tests/Unit/GstCalculationTest.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Accountings/Routes/api.php`
  - `Modules/Accountings/Routes/web-settings.php`
  - `Modules/Accountings/Routes/web.php`
- Migration files:
  - `Modules/Accountings/Database/Migrations/2021_07_27_153820_add_accounting_permission.php`
  - `Modules/Accountings/Database/Migrations/2023_03_21_020513_create_acc_map_pnl_table.php`
  - `Modules/Accountings/Database/Migrations/2023_03_21_021008_create_acc_type_journal_table.php`
  - `Modules/Accountings/Database/Migrations/2023_03_21_021159_create_acc_map_bs_table.php`
  - `Modules/Accountings/Database/Migrations/2023_03_21_021171_create_acc_coa_table.php`
  - `Modules/Accountings/Database/Migrations/2023_03_21_021217_create_acc_journalh_table.php`
  - `Modules/Accountings/Database/Migrations/2023_03_21_021319_create_acc_journald_table.php`
  - `Modules/Accountings/Database/Migrations/2025_12_28_131000_add_is_cash_account_to_acc_coa_table.php`
  - `Modules/Accountings/Database/Migrations/2025_12_28_134500_create_acc_recurring_expenses_table.php`
  - `Modules/Accountings/Database/Migrations/2025_12_28_134600_create_acc_cashflow_budgets_table.php`
  - ... and 15 more
- Test files:
  - `Modules/Accountings/Tests/Feature/ChartOfAccountsTest.php`
  - `Modules/Accountings/Tests/Feature/XeroSyncTest.php`
  - `Modules/Accountings/Tests/Unit/GstCalculationTest.php`

## Aitools

- **Alias:** `aitools`
- **Activation status map key:** `Aitools` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Aitools/module.json`
- **Route files:** 2
- **Migrations:** 29
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Aitools
# 2) run module migrations
php artisan module:migrate Aitools
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter Aitools
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Aitools/Routes/chat.php`
  - `Modules/Aitools/Routes/web.php`
- Migration files:
  - `Modules/Aitools/Database/Migrations/2025_01_14_099999_create_aitools_global_settings_table.php`
  - `Modules/Aitools/Database/Migrations/2025_07_26_064306_add_module_and_module_settings_aitools.php`
  - `Modules/Aitools/Database/Migrations/2025_11_27_125428_create_ai_tools_settings_table.php`
  - `Modules/Aitools/Database/Migrations/2025_11_28_064701_create_ai_tools_usage_history_table.php`
  - `Modules/Aitools/Database/Migrations/2025_12_31_084058_add_total_requests_to_ai_tools_usage_history_table.php`
  - `Modules/Aitools/Database/Migrations/2026_01_19_071021_add_view_permission_for_admin.php`
  - `Modules/Aitools/Database/Migrations/2026_01_19_071025_add_view_permission_for_admin.php`
  - `Modules/Aitools/Database/Migrations/2026_02_02_000001_create_ai_tools_conversations.php`
  - `Modules/Aitools/Database/Migrations/2026_02_02_000001_create_ai_tools_usage_histories_table.php`
  - `Modules/Aitools/Database/Migrations/2026_02_02_000002_create_ai_tools_messages.php`
  - ... and 19 more

## Asset

- **Alias:** `asset`
- **Activation status map key:** `Asset` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Asset/module.json`
- **Route files:** 2
- **Migrations:** 25
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Asset
# 2) run module migrations
php artisan module:migrate Asset
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter Asset
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Asset/Routes/api.php`
  - `Modules/Asset/Routes/web.php`
- Migration files:
  - `Modules/Asset/Database/Migrations/2020_01_12_070130_create_asset_types_table.php`
  - `Modules/Asset/Database/Migrations/2020_01_12_070306_create_assets_table.php`
  - `Modules/Asset/Database/Migrations/2020_01_12_084528_create_asset_lending_history_table.php`
  - `Modules/Asset/Database/Migrations/2020_02_21_181854_create_asset_settings_table.php`
  - `Modules/Asset/Database/Migrations/2020_02_22_181854_add_column_image_in_assets_table.php`
  - `Modules/Asset/Database/Migrations/2020_02_28_161803_add_asset_module_modules_table.php`
  - `Modules/Asset/Database/Migrations/2020_03_08_065037_add_lender_column.php`
  - `Modules/Asset/Database/Migrations/2021_08_11_123557_add_owned_by_columns_assets.php`
  - `Modules/Asset/Database/Migrations/2021_08_25_092728_alter_allowed_permission_column_in_asset_permissions_table.php`
  - `Modules/Asset/Database/Migrations/2022_09_02_000000_add_company_id_assets_module_table.php`
  - ... and 15 more

## Biometric

- **Alias:** `biometric`
- **Activation status map key:** `Biometric` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Biometric/module.json`
- **Route files:** 2
- **Migrations:** 14
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Biometric
# 2) run module migrations
php artisan module:migrate Biometric
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter Biometric
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Biometric/Routes/api.php`
  - `Modules/Biometric/Routes/web.php`
- Migration files:
  - `Modules/Biometric/Database/Migrations/2024_08_28_090058_create_biometric_global_settings_table.php`
  - `Modules/Biometric/Database/Migrations/2024_08_28_090058_create_biometric_settings_table.php`
  - `Modules/Biometric/Database/Migrations/2024_11_12_113048_create_biometric_devices_table.php`
  - `Modules/Biometric/Database/Migrations/2024_11_13_113406_create_biometric_employees_table.php`
  - `Modules/Biometric/Database/Migrations/2024_11_28_110946_create_biometric_permissions.php`
  - `Modules/Biometric/Database/Migrations/2025_05_01_022209_create_biometric_attendances_table.php`
  - `Modules/Biometric/Database/Migrations/2025_05_15_092248_biometric_commands.php`
  - `Modules/Biometric/Database/Migrations/2025_06_01_113406_add_card_biometric_employees_table.php`
  - `Modules/Biometric/Database/Migrations/2025_06_02_113406_add_bio_photo_biometric_employees_table.php`
  - `Modules/Biometric/Database/Migrations/2025_06_03_113406_add_clockin_method_biometric_employees_table.php`
  - ... and 4 more

## BookingModule

- **Alias:** `bookingmodule`
- **Activation status map key:** `BookingModule` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/BookingModule/module.json`
- **Health check file:** `Modules/BookingModule/health/checks.php`
- **Route files:** 2
- **Migrations:** 68
- **Tests:** 1

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable BookingModule
# 2) run module migrations
php artisan module:migrate BookingModule
# 3) run module tests
php artisan test Modules/BookingModule/Tests/Feature/CleaningBookingTest.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/BookingModule/Routes/api/v1/api.php`
  - `Modules/BookingModule/Routes/web.php`
- Migration files:
  - `Modules/BookingModule/Database/Migrations/2022_06_05_061932_create_bookings_table.php`
  - `Modules/BookingModule/Database/Migrations/2022_06_05_063828_create_booking_details_table.php`
  - `Modules/BookingModule/Database/Migrations/2022_06_05_065027_create_booking_status_histories_table.php`
  - `Modules/BookingModule/Database/Migrations/2022_06_05_065040_create_booking_schedule_histories_table.php`
  - `Modules/BookingModule/Database/Migrations/2022_06_11_074614_category_sub_added_booking.php`
  - `Modules/BookingModule/Database/Migrations/2022_06_19_063119_add_serviceman_col.php`
  - `Modules/BookingModule/Database/Migrations/2022_07_19_040550_change-col-name.php`
  - `Modules/BookingModule/Database/Migrations/2022_07_21_104205_add_booking_id_col.php`
  - `Modules/BookingModule/Database/Migrations/2022_08_06_031433_add_col_in_booking_table.php`
  - `Modules/BookingModule/Database/Migrations/2022_08_06_031649_add_col_in_booking_details_table.php`
  - ... and 58 more
- Test files:
  - `Modules/BookingModule/Tests/Feature/CleaningBookingTest.php`

## BusinessSettingsModule

- **Alias:** `businesssettingsmodule`
- **Activation status map key:** `BusinessSettingsModule` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/BusinessSettingsModule/module.json`
- **Health check file:** `Modules/BusinessSettingsModule/health/checks.php`
- **Route files:** 3
- **Migrations:** 32
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable BusinessSettingsModule
# 2) run module migrations
php artisan module:migrate BusinessSettingsModule
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter BusinessSettingsModule
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/BusinessSettingsModule/Routes/api.php`
  - `Modules/BusinessSettingsModule/Routes/api/v1/api.php`
  - `Modules/BusinessSettingsModule/Routes/web.php`
- Migration files:
  - `Modules/BusinessSettingsModule/Database/Migrations/2022_05_25_054015_create_business_settings_table.php`
  - `Modules/BusinessSettingsModule/Database/Migrations/2022_08_28_044249_col_change_to_business_settings_table.php`
  - `Modules/BusinessSettingsModule/Database/Migrations/2023_10_31_171211_create_translations_table.php`
  - `Modules/BusinessSettingsModule/Database/Migrations/2023_11_07_182712_create_landing_page_features_table.php`
  - `Modules/BusinessSettingsModule/Database/Migrations/2023_11_08_092558_create_landing_page_specialities_table.php`
  - `Modules/BusinessSettingsModule/Database/Migrations/2023_11_08_094847_create_landing_page_testimonials_table.php`
  - `Modules/BusinessSettingsModule/Database/Migrations/2023_11_16_110101_create_data_settings_table.php`
  - `Modules/BusinessSettingsModule/Database/Migrations/2024_05_21_105847_create_subscription_packages_table.php`
  - `Modules/BusinessSettingsModule/Database/Migrations/2024_05_21_105930_create_subscription_package_features_table.php`
  - `Modules/BusinessSettingsModule/Database/Migrations/2024_05_21_105958_create_subscription_package_limits_table.php`
  - ... and 22 more

## ClientPulse

- **Alias:** `clientpulse`
- **Activation status map key:** `ClientPulse` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/ClientPulse/module.json`
- **Route files:** 1
- **Migrations:** 3
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable ClientPulse
# 2) run module migrations
php artisan module:migrate ClientPulse
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter ClientPulse
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/ClientPulse/Routes/web.php`
- Migration files:
  - `Modules/ClientPulse/Database/Migrations/2026_04_10_700001_create_client_pulse_job_ratings_table.php`
  - `Modules/ClientPulse/Database/Migrations/2026_04_10_700002_create_client_pulse_extras_items_table.php`
  - `Modules/ClientPulse/Database/Migrations/2026_04_10_700003_create_client_pulse_extras_requests_table.php`

## Complaint

- **Alias:** `complaint`
- **Activation status map key:** `Complaint` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Complaint/module.json`
- **Route files:** 3
- **Migrations:** 17
- **Tests:** 2

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Complaint
# 2) run module migrations
php artisan module:migrate Complaint
# 3) run module tests
php artisan test Modules/Complaint/Tests/Feature/ComplaintTicketOverlayTest.php Modules/Complaint/Tests/Unit/ComplaintRefundValidationTest.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Complaint/Routes/api.php`
  - `Modules/Complaint/Routes/web-settings.php`
  - `Modules/Complaint/Routes/web.php`
- Migration files:
  - `Modules/Complaint/Database/Migrations/2021_07_27_203818_add_complaint_permission.php`
  - `Modules/Complaint/Database/Migrations/2023_03_21_021020_create_complaint_channels_table.php`
  - `Modules/Complaint/Database/Migrations/2023_03_21_021021_create_complaint_types_table.php`
  - `Modules/Complaint/Database/Migrations/2023_03_21_021023_create_complaint_groups_table.php`
  - `Modules/Complaint/Database/Migrations/2023_03_21_021023_create_complaint_table.php`
  - `Modules/Complaint/Database/Migrations/2023_03_21_021024_create_complaint_replies_table.php`
  - `Modules/Complaint/Database/Migrations/2023_03_21_021030_create_complaint_agent_groups_table.php`
  - `Modules/Complaint/Database/Migrations/2023_03_21_021030_create_complaint_custom_forms_table.php`
  - `Modules/Complaint/Database/Migrations/2023_03_21_021030_create_complaint_files_table.php`
  - `Modules/Complaint/Database/Migrations/2023_03_21_021030_create_complaint_reply_templates_table.php`
  - ... and 7 more
- Test files:
  - `Modules/Complaint/Tests/Feature/ComplaintTicketOverlayTest.php`
  - `Modules/Complaint/Tests/Unit/ComplaintRefundValidationTest.php`

## CustomerFeedback

- **Alias:** `customer-feedback`
- **Activation status map key:** `CustomerFeedback` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/CustomerFeedback/module.json`
- **Route files:** 2
- **Migrations:** 5
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable CustomerFeedback
# 2) run module migrations
php artisan module:migrate CustomerFeedback
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter CustomerFeedback
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/CustomerFeedback/Routes/api.php`
  - `Modules/CustomerFeedback/Routes/web.php`
- Migration files:
  - `Modules/CustomerFeedback/Database/Migrations/2025_03_22_000001_create_feedback_tickets_table.php`
  - `Modules/CustomerFeedback/Database/Migrations/2025_03_22_000002_create_feedback_replies_table.php`
  - `Modules/CustomerFeedback/Database/Migrations/2025_03_22_000003_create_support_tables.php`
  - `Modules/CustomerFeedback/Database/Migrations/2025_03_22_000004_create_survey_tables.php`
  - `Modules/CustomerFeedback/Database/Migrations/2025_03_22_000005_add_rating_to_employee_details.php`

## CustomerModule

- **Alias:** `customermodule`
- **Activation status map key:** `CustomerModule` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/CustomerModule/module.json`
- **Health check file:** `Modules/CustomerModule/health/checks.php`
- **Route files:** 2
- **Migrations:** 8
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable CustomerModule
# 2) run module migrations
php artisan module:migrate CustomerModule
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter CustomerModule
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/CustomerModule/Routes/api/v1/api.php`
  - `Modules/CustomerModule/Routes/web.php`
- Migration files:
  - `Modules/CustomerModule/Database/Migrations/2022_07_17_064031_add_addres_type.php`
  - `Modules/CustomerModule/Database/Migrations/2022_07_17_071324_add_addres_type1.php`
  - `Modules/CustomerModule/Database/Migrations/2023_02_05_225314_create_searched_data_table.php`
  - `Modules/CustomerModule/Database/Migrations/2023_07_19_111811_add_house_and_street_col_in_user_addresses_table.php`
  - `Modules/CustomerModule/Database/Migrations/2025_03_04_142506_create_subscribe_newsletters_table.php`
  - `Modules/CustomerModule/Database/Migrations/2026_03_02_090259_add_company_id_to_customermodule_tables.php`
  - `Modules/CustomerModule/Database/Migrations/2026_04_10_700001_add_fsm_columns_to_client_details_table.php`
  - `Modules/CustomerModule/Database/Migrations/2026_04_10_700002_create_client_addresses_table.php`

## EInvoice

- **Alias:** `einvoice`
- **Activation status map key:** `EInvoice` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/EInvoice/module.json`
- **Route files:** 2
- **Migrations:** 12
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable EInvoice
# 2) run module migrations
php artisan module:migrate EInvoice
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter EInvoice
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/EInvoice/Routes/api.php`
  - `Modules/EInvoice/Routes/web.php`
- Migration files:
  - `Modules/EInvoice/Database/Migrations/2023_11_03_071005_create_e_invoice_settings_table.php`
  - `Modules/EInvoice/Database/Migrations/2023_11_03_114439_create_e_invoice_company_settings_table.php`
  - `Modules/EInvoice/Database/Migrations/2023_11_07_105245_add_country_in_bussiness_address.php`
  - `Modules/EInvoice/Database/Migrations/2023_11_08_123050_add_e_invoice_column_to_client_details.php`
  - `Modules/EInvoice/Database/Migrations/2023_11_28_094141_e_invoice_license_type_update_global_settings.php`
  - `Modules/EInvoice/Database/Migrations/2023_12_19_091940_purchased_on_einvoice_setting_table.php`
  - `Modules/EInvoice/Database/Migrations/2025_09_29_120000_create_einvoice_ai_notes_table.php`
  - `Modules/EInvoice/Database/Migrations/2025_09_29_121500_create_einvoice_ai_drafts_table.php`
  - `Modules/EInvoice/Database/Migrations/2025_09_29_122800_create_einvoice_invoice_items_table.php`
  - `Modules/EInvoice/Database/Migrations/2025_09_29_122800_create_einvoice_invoices_table.php`
  - ... and 2 more

## EvidenceVault

- **Alias:** `evidence_vault`
- **Activation status map key:** `EvidenceVault` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/EvidenceVault/module.json`
- **Route files:** 2
- **Migrations:** 2
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable EvidenceVault
# 2) run module migrations
php artisan module:migrate EvidenceVault
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter EvidenceVault
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/EvidenceVault/Routes/api.php`
  - `Modules/EvidenceVault/Routes/web.php`
- Migration files:
  - `Modules/EvidenceVault/Database/Migrations/2024_01_01_000001_create_evidence_vault_submissions_table.php`
  - `Modules/EvidenceVault/Database/Migrations/2024_01_01_000002_create_evidence_vault_photos_table.php`

## Expenses

- **Alias:** `expenses`
- **Activation status map key:** `Expenses` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Expenses/module.json`
- **Route files:** 2
- **Migrations:** 7
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Expenses
# 2) run module migrations
php artisan module:migrate Expenses
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter Expenses
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Expenses/Routes/ai.php`
  - `Modules/Expenses/Routes/web.php`
- Migration files:
  - `Modules/Expenses/Database/Migrations/20251001_140000_add_approvals_to_expenses.php`
  - `Modules/Expenses/Database/Migrations/20251001_140100_create_expense_receipts_table.php`
  - `Modules/Expenses/Database/Migrations/create_expense_category_table.php`
  - `Modules/Expenses/Database/Migrations/create_expense_table.php`
  - `Modules/Expenses/Database/Migrations/create_income_category_table.php`
  - `Modules/Expenses/Database/Migrations/create_income_table.php`
  - `Modules/Expenses/Database/Migrations/create_ledger_table.php`

## FieldItems

- **Alias:** `items`
- **Activation status map key:** `FieldItems` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FieldItems/module.json`
- **Route files:** 2
- **Migrations:** 16
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FieldItems
# 2) run module migrations
php artisan module:migrate FieldItems
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FieldItems
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FieldItems/Routes/api.php`
  - `Modules/FieldItems/Routes/web.php`
- Migration files:
  - `Modules/FieldItems/Database/Migrations/2023_03_13_155330_create_item_category_table.php`
  - `Modules/FieldItems/Database/Migrations/2023_03_13_155354_create_item_sub_category_table.php`
  - `Modules/FieldItems/Database/Migrations/2023_03_13_155403_create_items_table.php`
  - `Modules/FieldItems/Database/Migrations/2023_03_13_155422_create_item_files_table.php`
  - `Modules/FieldItems/Database/Migrations/2023_03_13_161605_add_module_permission.php`
  - `Modules/FieldItems/Database/Migrations/2023_03_14_094023_add_company_id_to_item_category_table.php`
  - `Modules/FieldItems/Database/Migrations/2023_03_14_151908_add_module_to_custom_field_groups_table.php`
  - `Modules/FieldItems/Database/Migrations/2023_03_15_062022_add_unit_id_to_items_table.php`
  - `Modules/FieldItems/Database/Migrations/2023_03_15_062023_add_company_id_to_item_files_table.php`
  - `Modules/FieldItems/Database/Migrations/2025_09_28_175452_create_items_setup_tables.php`
  - ... and 6 more

## FSMAccount

- **Alias:** `fsmaccount`
- **Activation status map key:** `FSMAccount` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMAccount/module.json`
- **Health check file:** `Modules/FSMAccount/health/checks.php`
- **Route files:** 2
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMAccount
# 2) run module migrations
php artisan module:migrate FSMAccount
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMAccount
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMAccount/Routes/api.php`
  - `Modules/FSMAccount/Routes/web.php`
- Migration files:
  - `Modules/FSMAccount/Database/Migrations/2026_04_11_000001_add_invoice_fields_to_fsm.php`

## FSMActivity

- **Alias:** `fsmactivity`
- **Activation status map key:** `FSMActivity` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMActivity/module.json`
- **Health check file:** `Modules/FSMActivity/health/checks.php`
- **Route files:** 1
- **Migrations:** 2
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMActivity
# 2) run module migrations
php artisan module:migrate FSMActivity
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMActivity
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMActivity/Routes/web.php`
- Migration files:
  - `Modules/FSMActivity/Database/Migrations/2026_04_10_200001_create_fsm_activity_types_table.php`
  - `Modules/FSMActivity/Database/Migrations/2026_04_10_200002_create_fsm_activities_table.php`

## FSMAvailability

- **Alias:** `fsmavailability`
- **Activation status map key:** `FSMAvailability` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMAvailability/module.json`
- **Health check file:** `Modules/FSMAvailability/health/checks.php`
- **Route files:** 1
- **Migrations:** 3
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMAvailability
# 2) run module migrations
php artisan module:migrate FSMAvailability
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMAvailability
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMAvailability/Routes/web.php`
- Migration files:
  - `Modules/FSMAvailability/Database/Migrations/2026_04_10_300001_create_fsm_availability_rules_table.php`
  - `Modules/FSMAvailability/Database/Migrations/2026_04_10_300002_create_fsm_availability_exceptions_table.php`
  - `Modules/FSMAvailability/Database/Migrations/2026_04_10_300003_add_availability_flagged_to_fsm_day_routes.php`

## FSMCalendar

- **Alias:** `fsmcalendar`
- **Activation status map key:** `FSMCalendar` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMCalendar/module.json`
- **Health check file:** `Modules/FSMCalendar/health/checks.php`
- **Route files:** 1
- **Migrations:** 0
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMCalendar
# 2) run module migrations
php artisan module:migrate FSMCalendar
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMCalendar
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMCalendar/Routes/web.php`

## FSMCore

- **Alias:** `fsmcore`
- **Activation status map key:** `FSMCore` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMCore/module.json`
- **Health check file:** `Modules/FSMCore/health/checks.php`
- **Route files:** 2
- **Migrations:** 11
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMCore
# 2) run module migrations
php artisan module:migrate FSMCore
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMCore
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMCore/Routes/api.php`
  - `Modules/FSMCore/Routes/web.php`
- Migration files:
  - `Modules/FSMCore/Database/Migrations/2026_04_10_000001_create_fsm_stages_table.php`
  - `Modules/FSMCore/Database/Migrations/2026_04_10_000002_create_fsm_territories_table.php`
  - `Modules/FSMCore/Database/Migrations/2026_04_10_000003_create_fsm_locations_table.php`
  - `Modules/FSMCore/Database/Migrations/2026_04_10_000004_create_fsm_teams_table.php`
  - `Modules/FSMCore/Database/Migrations/2026_04_10_000005_create_fsm_tags_table.php`
  - `Modules/FSMCore/Database/Migrations/2026_04_10_000006_create_fsm_equipment_table.php`
  - `Modules/FSMCore/Database/Migrations/2026_04_10_000007_create_fsm_templates_table.php`
  - `Modules/FSMCore/Database/Migrations/2026_04_10_000008_create_fsm_orders_table.php`
  - `Modules/FSMCore/Database/Migrations/2026_04_10_000009_create_fsm_order_photos_table.php`
  - `Modules/FSMCore/Database/Migrations/2026_04_13_000010_add_fsm_core_permissions.php`
  - ... and 1 more

## FSMCRM

- **Alias:** `fsmcrm`
- **Activation status map key:** `FSMCRM` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMCRM/module.json`
- **Health check file:** `Modules/FSMCRM/health/checks.php`
- **Route files:** 1
- **Migrations:** 2
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMCRM
# 2) run module migrations
php artisan module:migrate FSMCRM
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMCRM
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMCRM/Routes/web.php`
- Migration files:
  - `Modules/FSMCRM/Database/Migrations/2026_04_10_400001_create_fsm_leads_table.php`
  - `Modules/FSMCRM/Database/Migrations/2026_04_10_400002_add_lead_id_to_fsm_orders_table.php`

## FSMEquipment

- **Alias:** `fsmequipment`
- **Activation status map key:** `FSMEquipment` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMEquipment/module.json`
- **Health check file:** `Modules/FSMEquipment/health/checks.php`
- **Route files:** 1
- **Migrations:** 3
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMEquipment
# 2) run module migrations
php artisan module:migrate FSMEquipment
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMEquipment
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMEquipment/Routes/web.php`
- Migration files:
  - `Modules/FSMEquipment/Database/Migrations/2026_04_10_200001_create_fsm_repair_order_templates_table.php`
  - `Modules/FSMEquipment/Database/Migrations/2026_04_10_200002_create_fsm_repair_orders_table.php`
  - `Modules/FSMEquipment/Database/Migrations/2026_04_10_200003_create_fsm_equipment_warranties_table.php`

## FSMEquipmentWarranty

- **Alias:** `fsmequipmentwarranty`
- **Activation status map key:** `FSMEquipmentWarranty` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMEquipmentWarranty/module.json`
- **Health check file:** `Modules/FSMEquipmentWarranty/health/checks.php`
- **Route files:** 1
- **Migrations:** 2
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMEquipmentWarranty
# 2) run module migrations
php artisan module:migrate FSMEquipmentWarranty
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMEquipmentWarranty
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMEquipmentWarranty/Routes/web.php`
- Migration files:
  - `Modules/FSMEquipmentWarranty/Database/Migrations/2026_04_20_200001_create_fsm_warranty_profiles_table.php`
  - `Modules/FSMEquipmentWarranty/Database/Migrations/2026_04_20_200002_add_warranty_profile_id_to_fsm_equipment_warranties.php`

## FSMKanban

- **Alias:** `fsmkanban`
- **Activation status map key:** `FSMKanban` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMKanban/module.json`
- **Health check file:** `Modules/FSMKanban/health/checks.php`
- **Route files:** 2
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMKanban
# 2) run module migrations
php artisan module:migrate FSMKanban
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMKanban
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMKanban/Routes/api.php`
  - `Modules/FSMKanban/Routes/web.php`
- Migration files:
  - `Modules/FSMKanban/Database/Migrations/2026_04_11_000001_add_kanban_fields_to_fsm_orders.php`

## FSMPortal

- **Alias:** `fsmportal`
- **Activation status map key:** `FSMPortal` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMPortal/module.json`
- **Health check file:** `Modules/FSMPortal/health/checks.php`
- **Route files:** 1
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMPortal
# 2) run module migrations
php artisan module:migrate FSMPortal
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMPortal
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMPortal/Routes/web.php`
- Migration files:
  - `Modules/FSMPortal/Database/Migrations/2026_04_10_600001_create_fsm_portal_reclean_requests_table.php`

## FSMProject

- **Alias:** `fsmproject`
- **Activation status map key:** `FSMProject` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMProject/module.json`
- **Health check file:** `Modules/FSMProject/health/checks.php`
- **Route files:** 2
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMProject
# 2) run module migrations
php artisan module:migrate FSMProject
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMProject
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMProject/Routes/api.php`
  - `Modules/FSMProject/Routes/web.php`
- Migration files:
  - `Modules/FSMProject/Database/Migrations/2026_04_11_000001_add_project_fields_to_fsm.php`

## FSMRecurring

- **Alias:** `fsmrecurring`
- **Activation status map key:** `FSMRecurring` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMRecurring/module.json`
- **Health check file:** `Modules/FSMRecurring/health/checks.php`
- **Route files:** 1
- **Migrations:** 3
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMRecurring
# 2) run module migrations
php artisan module:migrate FSMRecurring
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMRecurring
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMRecurring/Routes/web.php`
- Migration files:
  - `Modules/FSMRecurring/Database/Migrations/2026_04_10_010001_create_fsm_frequencies_table.php`
  - `Modules/FSMRecurring/Database/Migrations/2026_04_10_010002_create_fsm_recurrings_table.php`
  - `Modules/FSMRecurring/Database/Migrations/2026_04_10_010003_add_fsm_recurring_id_to_fsm_orders.php`

## FSMRepair

- **Alias:** `fsmrepair`
- **Activation status map key:** `FSMRepair` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMRepair/module.json`
- **Health check file:** `Modules/FSMRepair/health/checks.php`
- **Route files:** 2
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMRepair
# 2) run module migrations
php artisan module:migrate FSMRepair
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMRepair
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMRepair/Routes/api.php`
  - `Modules/FSMRepair/Routes/web.php`
- Migration files:
  - `Modules/FSMRepair/Database/Migrations/2026_04_11_000001_create_fsm_repair_orders_table.php`

## FSMRepairTemplate

- **Alias:** `fsmrepairtemplate`
- **Activation status map key:** `FSMRepairTemplate` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMRepairTemplate/module.json`
- **Health check file:** `Modules/FSMRepairTemplate/health/checks.php`
- **Route files:** 2
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMRepairTemplate
# 2) run module migrations
php artisan module:migrate FSMRepairTemplate
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMRepairTemplate
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMRepairTemplate/Routes/api.php`
  - `Modules/FSMRepairTemplate/Routes/web.php`
- Migration files:
  - `Modules/FSMRepairTemplate/Database/Migrations/2026_04_11_000001_add_repair_template_to_fsm.php`

## FSMRoute

- **Alias:** `fsmroute`
- **Activation status map key:** `FSMRoute` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMRoute/module.json`
- **Health check file:** `Modules/FSMRoute/health/checks.php`
- **Route files:** 1
- **Migrations:** 6
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMRoute
# 2) run module migrations
php artisan module:migrate FSMRoute
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMRoute
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMRoute/Routes/web.php`
- Migration files:
  - `Modules/FSMRoute/Database/Migrations/2026_04_10_000100_create_fsm_route_days_table.php`
  - `Modules/FSMRoute/Database/Migrations/2026_04_10_000101_create_fsm_routes_table.php`
  - `Modules/FSMRoute/Database/Migrations/2026_04_10_000102_create_fsm_day_routes_table.php`
  - `Modules/FSMRoute/Database/Migrations/2026_04_10_000103_extend_fsm_orders_for_routes.php`
  - `Modules/FSMRoute/Database/Migrations/2026_04_10_000104_create_fsm_worker_availability_table.php`
  - `Modules/FSMRoute/Database/Migrations/2026_04_10_000105_create_fsm_worker_location_pings_table.php`

## FSMRouteAvailability

- **Alias:** `fsmrouteavailability`
- **Activation status map key:** `FSMRouteAvailability` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMRouteAvailability/module.json`
- **Health check file:** `Modules/FSMRouteAvailability/health/checks.php`
- **Route files:** 1
- **Migrations:** 4
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMRouteAvailability
# 2) run module migrations
php artisan module:migrate FSMRouteAvailability
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMRouteAvailability
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMRouteAvailability/Routes/web.php`
- Migration files:
  - `Modules/FSMRouteAvailability/Database/Migrations/2026_04_20_300001_create_fsm_blackout_groups_table.php`
  - `Modules/FSMRouteAvailability/Database/Migrations/2026_04_20_300002_create_fsm_blackout_days_table.php`
  - `Modules/FSMRouteAvailability/Database/Migrations/2026_04_20_300003_create_fsm_route_blackout_group_pivot.php`
  - `Modules/FSMRouteAvailability/Database/Migrations/2026_04_20_300004_add_fsm_route_availability_permissions.php`

## FSMSaleAgreement

- **Alias:** `fsmsaleagreement`
- **Activation status map key:** `FSMSaleAgreement` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMSaleAgreement/module.json`
- **Health check file:** `Modules/FSMSaleAgreement/health/checks.php`
- **Route files:** 1
- **Migrations:** 0
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMSaleAgreement
# 2) run module migrations
php artisan module:migrate FSMSaleAgreement
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMSaleAgreement
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMSaleAgreement/Routes/web.php`

## FSMSaleRecurring

- **Alias:** `fsmsalerecurring`
- **Activation status map key:** `FSMSaleRecurring` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMSaleRecurring/module.json`
- **Health check file:** `Modules/FSMSaleRecurring/health/checks.php`
- **Route files:** 1
- **Migrations:** 2
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMSaleRecurring
# 2) run module migrations
php artisan module:migrate FSMSaleRecurring
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMSaleRecurring
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMSaleRecurring/Routes/web.php`
- Migration files:
  - `Modules/FSMSaleRecurring/Database/Migrations/2026_04_20_500001_add_invoice_line_id_to_fsm_recurrings.php`
  - `Modules/FSMSaleRecurring/Database/Migrations/2026_04_20_500002_add_fsm_sale_recurring_permissions.php`

## FSMSaleRecurringAgreement

- **Alias:** `fsmsalerecurringagreement`
- **Activation status map key:** `FSMSaleRecurringAgreement` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMSaleRecurringAgreement/module.json`
- **Health check file:** `Modules/FSMSaleRecurringAgreement/health/checks.php`
- **Route files:** 1
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMSaleRecurringAgreement
# 2) run module migrations
php artisan module:migrate FSMSaleRecurringAgreement
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMSaleRecurringAgreement
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMSaleRecurringAgreement/Routes/web.php`
- Migration files:
  - `Modules/FSMSaleRecurringAgreement/Database/Migrations/2026_04_20_600001_add_agreement_id_to_fsm_recurrings.php`

## FSMSales

- **Alias:** `fsmsales`
- **Activation status map key:** `FSMSales` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMSales/module.json`
- **Health check file:** `Modules/FSMSales/health/checks.php`
- **Route files:** 1
- **Migrations:** 5
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMSales
# 2) run module migrations
php artisan module:migrate FSMSales
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMSales
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMSales/Routes/web.php`
- Migration files:
  - `Modules/FSMSales/Database/Migrations/2026_04_10_500001_add_billing_fields_to_fsm_orders.php`
  - `Modules/FSMSales/Database/Migrations/2026_04_10_500002_create_fsm_sales_invoices_table.php`
  - `Modules/FSMSales/Database/Migrations/2026_04_10_500003_create_fsm_sales_invoice_lines_table.php`
  - `Modules/FSMSales/Database/Migrations/2026_04_10_500004_create_fsm_recurring_invoices_table.php`
  - `Modules/FSMSales/Database/Migrations/2026_04_20_500005_replace_fsm_sales_invoices_with_native_invoices.php`

## FSMSaleStock

- **Alias:** `fsmsalestock`
- **Activation status map key:** `FSMSaleStock` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMSaleStock/module.json`
- **Health check file:** `Modules/FSMSaleStock/health/checks.php`
- **Route files:** 1
- **Migrations:** 3
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMSaleStock
# 2) run module migrations
php artisan module:migrate FSMSaleStock
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMSaleStock
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMSaleStock/Routes/web.php`
- Migration files:
  - `Modules/FSMSaleStock/Database/Migrations/2026_04_20_700001_create_fsm_stock_requisitions_table.php`
  - `Modules/FSMSaleStock/Database/Migrations/2026_04_20_700002_create_fsm_stock_requisition_lines_table.php`
  - `Modules/FSMSaleStock/Database/Migrations/2026_04_20_700003_add_fsm_sale_stock_permissions.php`

## FSMServiceAgreement

- **Alias:** `fsmserviceagreement`
- **Activation status map key:** `FSMServiceAgreement` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMServiceAgreement/module.json`
- **Health check file:** `Modules/FSMServiceAgreement/health/checks.php`
- **Route files:** 1
- **Migrations:** 4
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMServiceAgreement
# 2) run module migrations
php artisan module:migrate FSMServiceAgreement
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMServiceAgreement
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMServiceAgreement/Routes/web.php`
- Migration files:
  - `Modules/FSMServiceAgreement/Database/Migrations/2026_04_10_000100_create_fsm_service_agreements_table.php`
  - `Modules/FSMServiceAgreement/Database/Migrations/2026_04_10_000101_create_fsm_agreement_lines_table.php`
  - `Modules/FSMServiceAgreement/Database/Migrations/2026_04_10_000102_add_agreement_id_to_fsm_orders.php`
  - `Modules/FSMServiceAgreement/Database/Migrations/2026_04_13_000103_add_fsm_service_agreement_permissions.php`

## FSMSize

- **Alias:** `fsmsize`
- **Activation status map key:** `FSMSize` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMSize/module.json`
- **Health check file:** `Modules/FSMSize/health/checks.php`
- **Route files:** 2
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMSize
# 2) run module migrations
php artisan module:migrate FSMSize
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMSize
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMSize/Routes/api.php`
  - `Modules/FSMSize/Routes/web.php`
- Migration files:
  - `Modules/FSMSize/Database/Migrations/2026_04_11_000001_create_fsm_sizes_table.php`

## FSMSkill

- **Alias:** `fsmskill`
- **Activation status map key:** `FSMSkill` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMSkill/module.json`
- **Health check file:** `Modules/FSMSkill/health/checks.php`
- **Route files:** 1
- **Migrations:** 5
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMSkill
# 2) run module migrations
php artisan module:migrate FSMSkill
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMSkill
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMSkill/Routes/web.php`
- Migration files:
  - `Modules/FSMSkill/Database/Migrations/2026_04_10_100001_create_fsm_skill_types_table.php`
  - `Modules/FSMSkill/Database/Migrations/2026_04_10_100002_create_fsm_skills_table.php`
  - `Modules/FSMSkill/Database/Migrations/2026_04_10_100003_create_fsm_skill_levels_table.php`
  - `Modules/FSMSkill/Database/Migrations/2026_04_10_100004_create_fsm_employee_skills_table.php`
  - `Modules/FSMSkill/Database/Migrations/2026_04_10_100005_create_fsm_skill_requirements_tables.php`

## FSMStageAction

- **Alias:** `fsmstageaction`
- **Activation status map key:** `FSMStageAction` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMStageAction/module.json`
- **Health check file:** `Modules/FSMStageAction/health/checks.php`
- **Route files:** 2
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMStageAction
# 2) run module migrations
php artisan module:migrate FSMStageAction
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMStageAction
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMStageAction/Routes/api.php`
  - `Modules/FSMStageAction/Routes/web.php`
- Migration files:
  - `Modules/FSMStageAction/Database/Migrations/2026_04_11_000001_create_fsm_stage_actions_table.php`

## FSMStock

- **Alias:** `fsmstock`
- **Activation status map key:** `FSMStock` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMStock/module.json`
- **Health check file:** `Modules/FSMStock/health/checks.php`
- **Route files:** 1
- **Migrations:** 6
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMStock
# 2) run module migrations
php artisan module:migrate FSMStock
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMStock
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMStock/Routes/web.php`
- Migration files:
  - `Modules/FSMStock/Database/Migrations/2026_04_10_300001_create_fsm_stock_categories_table.php`
  - `Modules/FSMStock/Database/Migrations/2026_04_10_300002_create_fsm_stock_items_table.php`
  - `Modules/FSMStock/Database/Migrations/2026_04_10_300003_create_fsm_order_stock_lines_table.php`
  - `Modules/FSMStock/Database/Migrations/2026_04_10_300004_create_fsm_stock_moves_table.php`
  - `Modules/FSMStock/Database/Migrations/2026_04_10_300005_create_fsm_location_equipment_registers_table.php`
  - `Modules/FSMStock/Database/Migrations/2026_04_10_300006_create_fsm_equipment_check_events_table.php`

## FSMTerritory

- **Alias:** `fsmterritory`
- **Activation status map key:** `FSMTerritory` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMTerritory/module.json`
- **Health check file:** `Modules/FSMTerritory/health/checks.php`
- **Route files:** 1
- **Migrations:** 6
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMTerritory
# 2) run module migrations
php artisan module:migrate FSMTerritory
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMTerritory
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMTerritory/Routes/web.php`
- Migration files:
  - `Modules/FSMTerritory/Database/Migrations/2026_04_20_100001_create_fsm_regions_table.php`
  - `Modules/FSMTerritory/Database/Migrations/2026_04_20_100002_create_fsm_districts_table.php`
  - `Modules/FSMTerritory/Database/Migrations/2026_04_20_100003_create_fsm_branches_table.php`
  - `Modules/FSMTerritory/Database/Migrations/2026_04_20_100004_create_fsm_territories_table.php`
  - `Modules/FSMTerritory/Database/Migrations/2026_04_20_100005_add_territory_id_to_fsm_locations.php`
  - `Modules/FSMTerritory/Database/Migrations/2026_04_20_100006_add_fsm_territory_permissions.php`

## FSMTimesheet

- **Alias:** `fsmtimesheet`
- **Activation status map key:** `FSMTimesheet` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMTimesheet/module.json`
- **Health check file:** `Modules/FSMTimesheet/health/checks.php`
- **Route files:** 1
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMTimesheet
# 2) run module migrations
php artisan module:migrate FSMTimesheet
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMTimesheet
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMTimesheet/Routes/web.php`
- Migration files:
  - `Modules/FSMTimesheet/Database/Migrations/2026_04_10_200001_create_fsm_timesheet_lines_table.php`

## FSMVehicle

- **Alias:** `fsmvehicle`
- **Activation status map key:** `FSMVehicle` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMVehicle/module.json`
- **Health check file:** `Modules/FSMVehicle/health/checks.php`
- **Route files:** 1
- **Migrations:** 4
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMVehicle
# 2) run module migrations
php artisan module:migrate FSMVehicle
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMVehicle
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMVehicle/Routes/web.php`
- Migration files:
  - `Modules/FSMVehicle/Database/Migrations/2026_04_10_000200_create_fsm_vehicles_table.php`
  - `Modules/FSMVehicle/Database/Migrations/2026_04_10_000201_create_fsm_vehicle_mileage_logs_table.php`
  - `Modules/FSMVehicle/Database/Migrations/2026_04_10_000202_add_vehicle_id_to_fsm_orders.php`
  - `Modules/FSMVehicle/Database/Migrations/2026_04_10_000203_add_vehicle_id_to_fsm_day_routes.php`

## FSMWorkflow

- **Alias:** `fsmworkflow`
- **Activation status map key:** `FSMWorkflow` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/FSMWorkflow/module.json`
- **Health check file:** `Modules/FSMWorkflow/health/checks.php`
- **Route files:** 1
- **Migrations:** 4
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable FSMWorkflow
# 2) run module migrations
php artisan module:migrate FSMWorkflow
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter FSMWorkflow
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/FSMWorkflow/Routes/web.php`
- Migration files:
  - `Modules/FSMWorkflow/Database/Migrations/2026_04_10_600001_create_fsm_stage_actions_table.php`
  - `Modules/FSMWorkflow/Database/Migrations/2026_04_10_600002_create_fsm_sizes_table.php`
  - `Modules/FSMWorkflow/Database/Migrations/2026_04_10_600003_add_size_fields_to_fsm_orders.php`
  - `Modules/FSMWorkflow/Database/Migrations/2026_04_10_600004_create_fsm_kanban_configs_table.php`

## Inspection

- **Alias:** `inspections`
- **Activation status map key:** `Inspection` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Inspection/module.json`
- **Health check file:** `Modules/Inspection/health/checks.php`
- **Route files:** 2
- **Migrations:** 13
- **Tests:** 81

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Inspection
# 2) run module migrations
php artisan module:migrate Inspection
# 3) run module tests
php artisan test Modules/Inspection/Tests/Feature/Pass1Stub01Test.php Modules/Inspection/Tests/Feature/Pass1Stub02Test.php Modules/Inspection/Tests/Feature/Pass1Stub03Test.php Modules/Inspection/Tests/Feature/Pass1Stub04Test.php Modules/Inspection/Tests/Feature/Pass1Stub05Test.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Inspection/Routes/api.php`
  - `Modules/Inspection/Routes/web.php`
- Migration files:
  - `Modules/Inspection/Database/Migrations/2023_02_25_013151_create_schedule_recurring_table.php`
  - `Modules/Inspection/Database/Migrations/2023_02_25_015641_create_schedule_recurring_items_table.php`
  - `Modules/Inspection/Database/Migrations/2023_02_25_020525_create_schedules_table.php`
  - `Modules/Inspection/Database/Migrations/2023_02_25_020625_create_schedule_items_table.php`
  - `Modules/Inspection/Database/Migrations/2023_02_25_034644_create_schedule_replies_table.php`
  - `Modules/Inspection/Database/Migrations/2023_02_25_034720_create_schedule_files_table.php`
  - `Modules/Inspection/Database/Migrations/2023_02_25_035831_create_inspection_module_permission_and_module_setting.php`
  - `Modules/Inspection/Database/Migrations/2025_12_23_030000_create_inspection_templates_table.php`
  - `Modules/Inspection/Database/Migrations/2025_12_23_030100_create_inspection_template_items_table.php`
  - `Modules/Inspection/Database/Migrations/2026_02_02_000002_inspection_seed_spatie_permissions.php`
  - ... and 3 more
- Test files:
  - `Modules/Inspection/Tests/Feature/Pass1Stub01Test.php`
  - `Modules/Inspection/Tests/Feature/Pass1Stub02Test.php`
  - `Modules/Inspection/Tests/Feature/Pass1Stub03Test.php`
  - `Modules/Inspection/Tests/Feature/Pass1Stub04Test.php`
  - `Modules/Inspection/Tests/Feature/Pass1Stub05Test.php`
  - `Modules/Inspection/Tests/Feature/Pass1Stub06Test.php`
  - `Modules/Inspection/Tests/Feature/Pass1Stub07Test.php`
  - `Modules/Inspection/Tests/Feature/Pass1Stub08Test.php`
  - `Modules/Inspection/Tests/Feature/Pass1Stub09Test.php`
  - `Modules/Inspection/Tests/Feature/Pass1Stub10Test.php`
  - ... and 71 more

## InstantAds

- **Alias:** `instantads`
- **Activation status map key:** `InstantAds` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/InstantAds/module.json`
- **Health check file:** `Modules/InstantAds/health/checks.php`
- **Route files:** 1
- **Migrations:** 1
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable InstantAds
# 2) run module migrations
php artisan module:migrate InstantAds
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter InstantAds
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/InstantAds/Routes/web.php`
- Migration files:
  - `Modules/InstantAds/Database/Migrations/2026_04_11_100001_create_instant_ads_tables.php`

## ManagedPremises

- **Alias:** `managedpremises`
- **Activation status map key:** `ManagedPremises` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/ManagedPremises/module.json`
- **Health check file:** `Modules/ManagedPremises/health/checks.php`
- **Route files:** 2
- **Migrations:** 27
- **Tests:** 6

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable ManagedPremises
# 2) run module migrations
php artisan module:migrate ManagedPremises
# 3) run module tests
php artisan test Modules/ManagedPremises/Tests/Feature/CalendarFeedTest.php Modules/ManagedPremises/Tests/Feature/CalendarPageTest.php Modules/ManagedPremises/Tests/Feature/GenerateVisitsCommandTest.php Modules/ManagedPremises/Tests/Feature/PropertySmokeTest.php Modules/ManagedPremises/Tests/Unit/IntegrationPointsTest.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/ManagedPremises/Routes/api.php`
  - `Modules/ManagedPremises/Routes/web.php`
- Migration files:
  - `Modules/ManagedPremises/Database/Migrations/2026_01_02_000001_create_pm_properties_table.php`
  - `Modules/ManagedPremises/Database/Migrations/2026_01_02_000002_create_pm_property_units_table.php`
  - `Modules/ManagedPremises/Database/Migrations/2026_01_02_000003_create_pm_property_contacts_table.php`
  - `Modules/ManagedPremises/Database/Migrations/2026_01_02_000004_create_pm_property_jobs_table.php`
  - `Modules/ManagedPremises/Database/Migrations/2026_01_02_000005_create_pm_property_keys_table.php`
  - `Modules/ManagedPremises/Database/Migrations/2026_01_02_000006_create_pm_property_photos_table.php`
  - `Modules/ManagedPremises/Database/Migrations/2026_01_02_000007_create_pm_property_checklists_table.php`
  - `Modules/ManagedPremises/Database/Migrations/2026_01_02_000008_create_pm_settings_table.php`
  - `Modules/ManagedPremises/Database/Migrations/2026_01_02_000009_create_pm_property_tags_table.php`
  - `Modules/ManagedPremises/Database/Migrations/2026_01_02_000010_create_pm_property_rooms_table.php`
  - ... and 17 more
- Test files:
  - `Modules/ManagedPremises/Tests/Feature/CalendarFeedTest.php`
  - `Modules/ManagedPremises/Tests/Feature/CalendarPageTest.php`
  - `Modules/ManagedPremises/Tests/Feature/GenerateVisitsCommandTest.php`
  - `Modules/ManagedPremises/Tests/Feature/PropertySmokeTest.php`
  - `Modules/ManagedPremises/Tests/Unit/IntegrationPointsTest.php`
  - `Modules/ManagedPremises/Tests/Unit/RecurrenceServiceTest.php`

## OnboardingPro

- **Alias:** `onboardingpro`
- **Activation status map key:** `OnboardingPro` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/OnboardingPro/module.json`
- **Health check file:** `Modules/OnboardingPro/health/checks.php`
- **Route files:** 1
- **Migrations:** 5
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable OnboardingPro
# 2) run module migrations
php artisan module:migrate OnboardingPro
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter OnboardingPro
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/OnboardingPro/Routes/web.php`
- Migration files:
  - `Modules/OnboardingPro/Database/Migrations/2026_04_11_800001_create_banners_table.php`
  - `Modules/OnboardingPro/Database/Migrations/2026_04_11_800002_create_banner_user_table.php`
  - `Modules/OnboardingPro/Database/Migrations/2026_04_11_800003_create_introduction_styles_table.php`
  - `Modules/OnboardingPro/Database/Migrations/2026_04_11_800004_create_surveys_table.php`
  - `Modules/OnboardingPro/Database/Migrations/2026_04_11_800005_create_survey_user_table.php`

## PromotionManagement

- **Alias:** `promotionmanagement`
- **Activation status map key:** `PromotionManagement` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/PromotionManagement/module.json`
- **Health check file:** `Modules/PromotionManagement/health/checks.php`
- **Route files:** 3
- **Migrations:** 22
- **Tests:** 2

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable PromotionManagement
# 2) run module migrations
php artisan module:migrate PromotionManagement
# 3) run module tests
php artisan test Modules/PromotionManagement/Tests/Feature/PromotionApplicationTest.php Modules/PromotionManagement/Tests/Unit/PromotionServiceTest.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/PromotionManagement/Routes/api.php`
  - `Modules/PromotionManagement/Routes/api/v1/api.php`
  - `Modules/PromotionManagement/Routes/web.php`
- Migration files:
  - `Modules/PromotionManagement/Database/Migrations/2022_03_06_091813_create_discounts_table.php`
  - `Modules/PromotionManagement/Database/Migrations/2022_03_07_063157_create_discount_types_table.php`
  - `Modules/PromotionManagement/Database/Migrations/2022_03_07_090055_create_coupons_table.php`
  - `Modules/PromotionManagement/Database/Migrations/2022_03_07_110744_create_campaigns_table.php`
  - `Modules/PromotionManagement/Database/Migrations/2022_03_08_052530_create_banners_table.php`
  - `Modules/PromotionManagement/Database/Migrations/2022_05_18_041330_discount_table_col_modify.php`
  - `Modules/PromotionManagement/Database/Migrations/2022_05_21_035041_add_coupon_type.php`
  - `Modules/PromotionManagement/Database/Migrations/2022_05_22_120123_add_banner_redirection_link.php`
  - `Modules/PromotionManagement/Database/Migrations/2022_07_03_095424_create_push_notifications_table.php`
  - `Modules/PromotionManagement/Database/Migrations/2023_05_16_231127_create_coupon_customers_table.php`
  - ... and 12 more
- Test files:
  - `Modules/PromotionManagement/Tests/Feature/PromotionApplicationTest.php`
  - `Modules/PromotionManagement/Tests/Unit/PromotionServiceTest.php`

## ProviderManagement

- **Alias:** `providermanagement`
- **Activation status map key:** `ProviderManagement` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/ProviderManagement/module.json`
- **Health check file:** `Modules/ProviderManagement/health/checks.php`
- **Route files:** 2
- **Migrations:** 21
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable ProviderManagement
# 2) run module migrations
php artisan module:migrate ProviderManagement
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter ProviderManagement
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/ProviderManagement/Routes/api/v1/api.php`
  - `Modules/ProviderManagement/Routes/web.php`
- Migration files:
  - `Modules/ProviderManagement/Database/Migrations/2022_03_07_064337_create_providers_table.php`
  - `Modules/ProviderManagement/Database/Migrations/2022_03_07_065305_create_provider_sub_category_table.php`
  - `Modules/ProviderManagement/Database/Migrations/2022_06_13_120346_add_column_is_approved_to_provider_table.php`
  - `Modules/ProviderManagement/Database/Migrations/2022_06_14_104816_create_bank_details_table.php`
  - `Modules/ProviderManagement/Database/Migrations/2022_06_15_043227_create_subscribed_services_table.php`
  - `Modules/ProviderManagement/Database/Migrations/2022_06_18_095222_create_withdraw_requests_table.php`
  - `Modules/ProviderManagement/Database/Migrations/2022_06_22_090257_column_add_to_withdraw_request_table.php`
  - `Modules/ProviderManagement/Database/Migrations/2022_07_03_065118_add_zone_id_in_providers.php`
  - `Modules/ProviderManagement/Database/Migrations/2022_09_17_185044_add_col_to_bank_destails.php`
  - `Modules/ProviderManagement/Database/Migrations/2022_09_21_235326_col_add_to_withdraw_requests_table.php`
  - ... and 11 more

## Purchase

- **Alias:** `purchase`
- **Activation status map key:** `Purchase` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Purchase/module.json`
- **Health check file:** `Modules/Purchase/health/checks.php`
- **Route files:** 2
- **Migrations:** 18
- **Tests:** 2

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Purchase
# 2) run module migrations
php artisan module:migrate Purchase
# 3) run module tests
php artisan test Modules/Purchase/Tests/Feature/PurchaseOrderLifecycleTest.php Modules/Purchase/Tests/Unit/PurchaseSupportTest.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Purchase/Routes/api.php`
  - `Modules/Purchase/Routes/web.php`
- Migration files:
  - `Modules/Purchase/Database/Migrations/2023_05_03_070320_create_vendor.php`
  - `Modules/Purchase/Database/Migrations/2023_05_03_112230_add_role_and_permission_in_permission_table.php`
  - `Modules/Purchase/Database/Migrations/2023_07_14_102505_product_files_timstamps.php`
  - `Modules/Purchase/Database/Migrations/2023_09_04_050707_product_files.php`
  - `Modules/Purchase/Database/Migrations/2023_10_23_071216_create_purchase_management_settings.php`
  - `Modules/Purchase/Database/Migrations/2023_11_02_094141_purchase_notify_update_global_settings.php`
  - `Modules/Purchase/Database/Migrations/2023_11_28_094141_purchase_license_type_update_global_settings.php`
  - `Modules/Purchase/Database/Migrations/2023_12_19_091940_purchased_on_purchase_setting_table.php`
  - `Modules/Purchase/Database/Migrations/2024_01_18_105833_create_view_purchase_settings_permission_in_permissions_table.php`
  - `Modules/Purchase/Database/Migrations/2024_04_29_122517_create_purchase_orders_table.php`
  - ... and 8 more
- Test files:
  - `Modules/Purchase/Tests/Feature/PurchaseOrderLifecycleTest.php`
  - `Modules/Purchase/Tests/Unit/PurchaseSupportTest.php`

## QualityControl

- **Alias:** `quality_control`
- **Activation status map key:** `QualityControl` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/QualityControl/module.json`
- **Health check file:** `Modules/QualityControl/health/checks.php`
- **Route files:** 2
- **Migrations:** 28
- **Tests:** 81

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable QualityControl
# 2) run module migrations
php artisan module:migrate QualityControl
# 3) run module tests
php artisan test Modules/QualityControl/Tests/Feature/Pass1Stub01Test.php Modules/QualityControl/Tests/Feature/Pass1Stub02Test.php Modules/QualityControl/Tests/Feature/Pass1Stub03Test.php Modules/QualityControl/Tests/Feature/Pass1Stub04Test.php Modules/QualityControl/Tests/Feature/Pass1Stub05Test.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/QualityControl/Routes/api.php`
  - `Modules/QualityControl/Routes/web.php`
- Migration files:
  - `Modules/QualityControl/Database/Migrations/2023_02_25_013151_create_schedule_recurring_table.php`
  - `Modules/QualityControl/Database/Migrations/2023_02_25_013151_qc_create_schedule_recurring_table.php`
  - `Modules/QualityControl/Database/Migrations/2023_02_25_015641_create_schedule_recurring_items_table.php`
  - `Modules/QualityControl/Database/Migrations/2023_02_25_015641_qc_create_schedule_recurring_items_table.php`
  - `Modules/QualityControl/Database/Migrations/2023_02_25_020525_create_schedules_table.php`
  - `Modules/QualityControl/Database/Migrations/2023_02_25_020525_qc_create_schedules_table.php`
  - `Modules/QualityControl/Database/Migrations/2023_02_25_020625_create_schedule_items_table.php`
  - `Modules/QualityControl/Database/Migrations/2023_02_25_020625_qc_create_schedule_items_table.php`
  - `Modules/QualityControl/Database/Migrations/2023_02_25_034644_create_schedule_replies_table.php`
  - `Modules/QualityControl/Database/Migrations/2023_02_25_034644_qc_create_schedule_replies_table.php`
  - ... and 18 more
- Test files:
  - `Modules/QualityControl/Tests/Feature/Pass1Stub01Test.php`
  - `Modules/QualityControl/Tests/Feature/Pass1Stub02Test.php`
  - `Modules/QualityControl/Tests/Feature/Pass1Stub03Test.php`
  - `Modules/QualityControl/Tests/Feature/Pass1Stub04Test.php`
  - `Modules/QualityControl/Tests/Feature/Pass1Stub05Test.php`
  - `Modules/QualityControl/Tests/Feature/Pass1Stub06Test.php`
  - `Modules/QualityControl/Tests/Feature/Pass1Stub07Test.php`
  - `Modules/QualityControl/Tests/Feature/Pass1Stub08Test.php`
  - `Modules/QualityControl/Tests/Feature/Pass1Stub09Test.php`
  - `Modules/QualityControl/Tests/Feature/Pass1Stub10Test.php`
  - ... and 71 more

## Report

- **Alias:** `report`
- **Activation status map key:** `Report` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Report/module.json`
- **Health check file:** `Modules/Report/health/checks.php`
- **Route files:** 1
- **Migrations:** 1
- **Tests:** 1

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Report
# 2) run module migrations
php artisan module:migrate Report
# 3) run module tests
php artisan test Modules/Report/Tests/Feature/BookingReportTest.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Report/Routes/web.php`
- Migration files:
  - `Modules/Report/Database/Migrations/2026_04_11_700001_seed_report_subscription_package_features.php`
- Test files:
  - `Modules/Report/Tests/Feature/BookingReportTest.php`

## RestAPI

- **Alias:** `restapi`
- **Activation status map key:** `RestAPI` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/RestAPI/module.json`
- **Health check file:** `Modules/RestAPI/health/checks.php`
- **Route files:** 2
- **Migrations:** 11
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable RestAPI
# 2) run module migrations
php artisan module:migrate RestAPI
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter RestAPI
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/RestAPI/Routes/api.php`
  - `Modules/RestAPI/Routes/web.php`
- Migration files:
  - `Modules/RestAPI/Database/Migrations/2019_12_14_000001_create_personal_access_tokens_table.php`
  - `Modules/RestAPI/Database/Migrations/2020_01_31_121040_api_settings.php`
  - `Modules/RestAPI/Database/Migrations/2020_02_01_085612_create_devices_table.php`
  - `Modules/RestAPI/Database/Migrations/2020_04_09_071616_update_rest_setting_firebase.php`
  - `Modules/RestAPI/Database/Migrations/2021_01_05_121040_application_settings.php`
  - `Modules/RestAPI/Database/Migrations/2021_09_15_123557_add_owned_by_columns_rest_api_settings.php`
  - `Modules/RestAPI/Database/Migrations/2022_09_02_000000_add_company_id_restapi_module_table.php`
  - `Modules/RestAPI/Database/Migrations/2023_11_02_094141_restapi_notify_update_global_settings.php`
  - `Modules/RestAPI/Database/Migrations/2023_11_28_094141_rest_license_type_update_global_settings.php`
  - `Modules/RestAPI/Database/Migrations/2023_12_19_091940_purchased_on_rest_setting_table.php`
  - ... and 1 more

## ReviewModule

- **Alias:** `reviewmodule`
- **Activation status map key:** `ReviewModule` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/ReviewModule/module.json`
- **Health check file:** `Modules/ReviewModule/health/checks.php`
- **Route files:** 2
- **Migrations:** 7
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable ReviewModule
# 2) run module migrations
php artisan module:migrate ReviewModule
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter ReviewModule
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/ReviewModule/Routes/api/v1/api.php`
  - `Modules/ReviewModule/Routes/web.php`
- Migration files:
  - `Modules/ReviewModule/Database/Migrations/2022_06_18_052537_create_reviews_table.php`
  - `Modules/ReviewModule/Database/Migrations/2022_07_24_051517_add_cus_col_in_review.php`
  - `Modules/ReviewModule/Database/Migrations/2024_09_02_131527_create_review_replies_table.php`
  - `Modules/ReviewModule/Database/Migrations/2024_09_02_132320_add_to_col_readable_id_in_review_table.php`
  - `Modules/ReviewModule/Database/Migrations/2026_03_02_090259_add_company_id_to_reviewmodule_tables.php`
  - `Modules/ReviewModule/Database/Migrations/2026_04_10_600001_add_cleaning_business_cols_to_reviews_table.php`
  - `Modules/ReviewModule/Database/Migrations/2026_04_11_600002_seed_reviews_subscription_package_feature.php`

## ServiceManagement

- **Alias:** `servicemanagement`
- **Activation status map key:** `ServiceManagement` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/ServiceManagement/module.json`
- **Health check file:** `Modules/ServiceManagement/health/checks.php`
- **Route files:** 2
- **Migrations:** 24
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable ServiceManagement
# 2) run module migrations
php artisan module:migrate ServiceManagement
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter ServiceManagement
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/ServiceManagement/Routes/api/v1/api.php`
  - `Modules/ServiceManagement/Routes/web.php`
- Migration files:
  - `Modules/ServiceManagement/Database/Migrations/2022_03_06_092202_create_services_table.php`
  - `Modules/ServiceManagement/Database/Migrations/2022_03_06_094413_create_variations_table.php`
  - `Modules/ServiceManagement/Database/Migrations/2022_05_09_122054_add_variant_key_in_variation.php`
  - `Modules/ServiceManagement/Database/Migrations/2022_05_12_100348_create_faqs_table.php`
  - `Modules/ServiceManagement/Database/Migrations/2022_12_05_184417_col_add_to_services_table.php`
  - `Modules/ServiceManagement/Database/Migrations/2022_12_06_002432_create_recent_views_table.php`
  - `Modules/ServiceManagement/Database/Migrations/2022_12_08_201359_create_recent_searches_table.php`
  - `Modules/ServiceManagement/Database/Migrations/2023_01_29_011739_create_tags_table.php`
  - `Modules/ServiceManagement/Database/Migrations/2023_01_29_162753_create_table_service_tag.php`
  - `Modules/ServiceManagement/Database/Migrations/2023_02_02_231012_create_service_requests_table.php`
  - ... and 14 more

## ServicemanModule

- **Alias:** `servicemanmodule`
- **Activation status map key:** `ServicemanModule` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/ServicemanModule/module.json`
- **Health check file:** `Modules/ServicemanModule/health/checks.php`
- **Route files:** 2
- **Migrations:** 4
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable ServicemanModule
# 2) run module migrations
php artisan module:migrate ServicemanModule
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter ServicemanModule
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/ServicemanModule/Routes/api/v1/api.php`
  - `Modules/ServicemanModule/Routes/web.php`
- Migration files:
  - `Modules/ServicemanModule/Database/Migrations/2026_04_10_600001_add_gps_columns_to_tasks_table.php`
  - `Modules/ServicemanModule/Database/Migrations/2026_04_10_600002_add_booking_id_to_attendances_table.php`
  - `Modules/ServicemanModule/Database/Migrations/2026_04_10_600003_create_job_photos_table.php`
  - `Modules/ServicemanModule/Database/Migrations/2026_04_10_600004_create_job_checklists_tables.php`

## Sms

- **Alias:** `sms`
- **Activation status map key:** `Sms` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Sms/module.json`
- **Health check file:** `Modules/Sms/health/checks.php`
- **Route files:** 2
- **Migrations:** 19
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Sms
# 2) run module migrations
php artisan module:migrate Sms
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter Sms
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Sms/Routes/api.php`
  - `Modules/Sms/Routes/web.php`
- Migration files:
  - `Modules/Sms/Database/Migrations/2020_07_07_085510_create_twilio_settings_table.php`
  - `Modules/Sms/Database/Migrations/2020_07_07_105427_add_send_twilio_column_email_notification_table.php`
  - `Modules/Sms/Database/Migrations/2021_06_16_123557_add_owned_by_columns_sms_settings.php`
  - `Modules/Sms/Database/Migrations/2021_10_21_063316_add_allowed_permissions.php`
  - `Modules/Sms/Database/Migrations/2021_12_13_165351_add_telegram_columns_settings.php`
  - `Modules/Sms/Database/Migrations/2022_03_29_090843_create_sms_notification_settings_table.php`
  - `Modules/Sms/Database/Migrations/2022_08_27_095940_ whatsapp_template_id_sms_notification_setting_table.php`
  - `Modules/Sms/Database/Migrations/2022_09_02_000000_add_company_id_sms_module_table.php`
  - `Modules/Sms/Database/Migrations/2023_07_20_165420_add_telegram_columns_settings.php`
  - `Modules/Sms/Database/Migrations/2023_09_29_143720_add_telegram_columns_settings.php`
  - ... and 9 more

## StaffCompliance

- **Alias:** `staffcompliance`
- **Activation status map key:** `StaffCompliance` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/StaffCompliance/module.json`
- **Health check file:** `Modules/StaffCompliance/health/checks.php`
- **Route files:** 1
- **Migrations:** 2
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable StaffCompliance
# 2) run module migrations
php artisan module:migrate StaffCompliance
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter StaffCompliance
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/StaffCompliance/Routes/web.php`
- Migration files:
  - `Modules/StaffCompliance/Database/Migrations/2026_04_11_700001_create_compliance_document_types_table.php`
  - `Modules/StaffCompliance/Database/Migrations/2026_04_11_700002_create_worker_compliance_documents_table.php`

## SynapseDispatch

- **Alias:** `synapsedispatch`
- **Activation status map key:** `SynapseDispatch` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/SynapseDispatch/module.json`
- **Health check file:** `Modules/SynapseDispatch/health/checks.php`
- **Route files:** 1
- **Migrations:** 5
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable SynapseDispatch
# 2) run module migrations
php artisan module:migrate SynapseDispatch
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter SynapseDispatch
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/SynapseDispatch/Routes/web.php`
- Migration files:
  - `Modules/SynapseDispatch/Database/Migrations/2024_01_01_000001_create_dispatch_locations_table.php`
  - `Modules/SynapseDispatch/Database/Migrations/2024_01_01_000002_create_dispatch_teams_table.php`
  - `Modules/SynapseDispatch/Database/Migrations/2024_01_01_000003_create_dispatch_workers_table.php`
  - `Modules/SynapseDispatch/Database/Migrations/2024_01_01_000004_create_dispatch_jobs_table.php`
  - `Modules/SynapseDispatch/Database/Migrations/2024_01_01_000005_create_dispatch_events_table.php`

## Testimonials

- **Alias:** `testimonials`
- **Activation status map key:** `Testimonials` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/Testimonials/module.json`
- **Health check file:** `Modules/Testimonials/health/checks.php`
- **Route files:** 2
- **Migrations:** 3
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable Testimonials
# 2) run module migrations
php artisan module:migrate Testimonials
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter Testimonials
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/Testimonials/Routes/api.php`
  - `Modules/Testimonials/Routes/web.php`
- Migration files:
  - `Modules/Testimonials/Database/Migrations/2024_11_03_192616_create_testimonials_table.php`
  - `Modules/Testimonials/Database/Migrations/2026_04_10_000001_add_cleaning_fields_to_testimonials_table.php`
  - `Modules/Testimonials/Database/Migrations/2026_04_10_000002_create_testimonial_widgets_table.php`

## TitanCore

- **Alias:** `titancore`
- **Activation status map key:** `TitanCore` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/TitanCore/module.json`
- **Health check file:** `Modules/TitanCore/health/checks.php`
- **Route files:** 2
- **Migrations:** 23
- **Tests:** 4

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable TitanCore
# 2) run module migrations
php artisan module:migrate TitanCore
# 3) run module tests
php artisan test Modules/TitanCore/Tests/Feature/MetricsApiTest.php Modules/TitanCore/Tests/Feature/PromptsApiTest.php Modules/TitanCore/Tests/Feature/RoutesTest.php Modules/TitanCore/Tests/Unit/AdapterTest.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/TitanCore/Routes/api.php`
  - `Modules/TitanCore/Routes/web.php`
- Migration files:
  - `Modules/TitanCore/Database/Migrations/2025_10_01_000001_create_ai_prompts.php`
  - `Modules/TitanCore/Database/Migrations/2025_10_01_000100_create_ai_kb_sources.php`
  - `Modules/TitanCore/Database/Migrations/2025_10_01_000110_create_ai_kb_documents.php`
  - `Modules/TitanCore/Database/Migrations/2025_10_01_000120_create_ai_kb_chunks.php`
  - `Modules/TitanCore/Database/Migrations/2025_10_01_000130_create_ai_kb_collections.php`
  - `Modules/TitanCore/Database/Migrations/2025_10_01_000140_create_ai_kb_collection_docs.php`
  - `Modules/TitanCore/Database/Migrations/2025_10_01_000200_create_ai_usage_ledger.php`
  - `Modules/TitanCore/Database/Migrations/2025_10_02_000200_create_ai_usage_table.php`
  - `Modules/TitanCore/Database/Migrations/2025_12_06_000900_add_titan_core_to_modules_table.php`
  - `Modules/TitanCore/Database/Migrations/2025_12_06_001000_add_titan_core_to_module_settings_table.php`
  - ... and 13 more
- Test files:
  - `Modules/TitanCore/Tests/Feature/MetricsApiTest.php`
  - `Modules/TitanCore/Tests/Feature/PromptsApiTest.php`
  - `Modules/TitanCore/Tests/Feature/RoutesTest.php`
  - `Modules/TitanCore/Tests/Unit/AdapterTest.php`

## TitanDocs

- **Alias:** `titandocs`
- **Activation status map key:** `TitanDocs` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/TitanDocs/module.json`
- **Health check file:** `Modules/TitanDocs/health/checks.php`
- **Route files:** 2
- **Migrations:** 16
- **Tests:** 2

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable TitanDocs
# 2) run module migrations
php artisan module:migrate TitanDocs
# 3) run module tests
php artisan test Modules/TitanDocs/Tests/Feature/DocumentTemplateImmutabilityTest.php Modules/TitanDocs/Tests/Unit/DocumentTemplateRenderTest.php
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/TitanDocs/Routes/api.php`
  - `Modules/TitanDocs/Routes/web.php`
- Migration files:
  - `Modules/TitanDocs/Database/Migrations/2023_06_10_043700_create_ai_templates_table.php`
  - `Modules/TitanDocs/Database/Migrations/2023_06_10_050110_create_ai_template_categories_table.php`
  - `Modules/TitanDocs/Database/Migrations/2023_06_10_051036_create_ai_template_languages_table.php`
  - `Modules/TitanDocs/Database/Migrations/2023_06_10_052024_create_ai_template_prompts_table.php`
  - `Modules/TitanDocs/Database/Migrations/2023_06_10_071056_create_ai_prompt_histories_table.php`
  - `Modules/TitanDocs/Database/Migrations/2023_06_10_101058_create_ai_prompt_responses_table.php`
  - `Modules/TitanDocs/Database/Migrations/2026_03_01_000010_add_kb_collection_key_to_ai_templates.php`
  - `Modules/TitanDocs/Database/Migrations/2026_03_01_000100_add_approval_fields_to_ai_templates.php`
  - `Modules/TitanDocs/Database/Migrations/2026_03_02_000200_seed_default_titandocs_templates.php`
  - `Modules/TitanDocs/Database/Migrations/2026_03_02_000300_register_titandocs_in_packages_registry.php`
  - ... and 6 more
- Test files:
  - `Modules/TitanDocs/Tests/Feature/DocumentTemplateImmutabilityTest.php`
  - `Modules/TitanDocs/Tests/Unit/DocumentTemplateRenderTest.php`

## TitanGo

- **Alias:** `titango`
- **Activation status map key:** `TitanGo` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/TitanGo/module.json`
- **Health check file:** `Modules/TitanGo/health/checks.php`
- **Route files:** 2
- **Migrations:** 8
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable TitanGo
# 2) run module migrations
php artisan module:migrate TitanGo
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter TitanGo
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/TitanGo/Routes/api.php`
  - `Modules/TitanGo/Routes/web.php`
- Migration files:
  - `Modules/TitanGo/Database/Migrations/2026_04_13_000001_create_titan_go_location_pings_table.php`
  - `Modules/TitanGo/Database/Migrations/2026_04_13_000002_create_titan_go_issues_table.php`
  - `Modules/TitanGo/Database/Migrations/2026_04_13_000003_create_titan_go_site_notes_table.php`
  - `Modules/TitanGo/Database/Migrations/2026_04_13_000004_create_titan_go_worker_statuses_table.php`
  - `Modules/TitanGo/Database/Migrations/2026_04_13_000005_create_nexus_job_notes_table.php`
  - `Modules/TitanGo/Database/Migrations/2026_04_13_000006_add_titan_go_columns_to_users_table.php`
  - `Modules/TitanGo/Database/Migrations/2026_04_13_000007_create_titan_go_checklist_completions_table.php`
  - `Modules/TitanGo/Database/Migrations/2026_04_13_000008_upgrade_fsm_template_checklist_to_rich_steps.php`

## TitanIntegrations

- **Alias:** `titanintegrations`
- **Activation status map key:** `TitanIntegrations` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/TitanIntegrations/module.json`
- **Health check file:** `Modules/TitanIntegrations/health/checks.php`
- **Route files:** 2
- **Migrations:** 4
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable TitanIntegrations
# 2) run module migrations
php artisan module:migrate TitanIntegrations
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter TitanIntegrations
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/TitanIntegrations/Routes/api.php`
  - `Modules/TitanIntegrations/Routes/web.php`
- Migration files:
  - `Modules/TitanIntegrations/Database/Migrations/2026_04_11_000001_create_integrations_table.php`
  - `Modules/TitanIntegrations/Database/Migrations/2026_04_11_000002_create_api_tokens_table.php`
  - `Modules/TitanIntegrations/Database/Migrations/2026_04_11_000003_create_webhook_endpoints_table.php`
  - `Modules/TitanIntegrations/Database/Migrations/2026_04_11_000004_add_titanintegrations_module_permission.php`

## TitanTheme

- **Alias:** `titantheme`
- **Activation status map key:** `TitanTheme` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/TitanTheme/module.json`
- **Health check file:** `Modules/TitanTheme/health/checks.php`
- **Route files:** 2
- **Migrations:** 6
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable TitanTheme
# 2) run module migrations
php artisan module:migrate TitanTheme
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter TitanTheme
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/TitanTheme/Routes/api.php`
  - `Modules/TitanTheme/Routes/web.php`
- Migration files:
  - `Modules/TitanTheme/Database/Migrations/2026_04_11_000001_create_titan_theme_presets_table.php`
  - `Modules/TitanTheme/Database/Migrations/2026_04_11_000002_create_titan_mega_menus_table.php`
  - `Modules/TitanTheme/Database/Migrations/2026_04_11_000003_create_titan_mega_menu_items_table.php`
  - `Modules/TitanTheme/Database/Migrations/2026_04_11_000004_create_titan_nav_items_table.php`
  - `Modules/TitanTheme/Database/Migrations/2026_04_12_000001_add_parent_id_to_menus_table.php`
  - `Modules/TitanTheme/Database/Migrations/2026_04_14_000005_register_titan_theme_module_wiring.php`

## TitanVault

- **Alias:** `titan_vault`
- **Activation status map key:** `TitanVault` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/TitanVault/module.json`
- **Health check file:** `Modules/TitanVault/health/checks.php`
- **Route files:** 2
- **Migrations:** 6
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable TitanVault
# 2) run module migrations
php artisan module:migrate TitanVault
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter TitanVault
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/TitanVault/Routes/api.php`
  - `Modules/TitanVault/Routes/web.php`
- Migration files:
  - `Modules/TitanVault/Database/Migrations/2026_04_10_000001_create_vault_documents_table.php`
  - `Modules/TitanVault/Database/Migrations/2026_04_10_000002_create_vault_document_versions_table.php`
  - `Modules/TitanVault/Database/Migrations/2026_04_10_000003_create_vault_document_comments_table.php`
  - `Modules/TitanVault/Database/Migrations/2026_04_10_000004_create_vault_approvals_table.php`
  - `Modules/TitanVault/Database/Migrations/2026_04_10_000005_create_vault_access_links_table.php`
  - `Modules/TitanVault/Database/Migrations/2026_04_10_000006_create_vault_activity_log_table.php`

## ZoneManagement

- **Alias:** `zonemanagement`
- **Activation status map key:** `ZoneManagement` (source: `storage/app/modules_statuses.json`)
- **Manifest:** `Modules/ZoneManagement/module.json`
- **Health check file:** `Modules/ZoneManagement/health/checks.php`
- **Route files:** 2
- **Migrations:** 10
- **Tests:** 0

### Executable Runbook
```bash
# 1) ensure module is enabled
php artisan module:enable ZoneManagement
# 2) run module migrations
php artisan module:migrate ZoneManagement
# 3) no module-local tests detected; run targeted integration fallback
php artisan test --filter ZoneManagement
```

### Acceptance Criteria
- Module routes load without collisions or unresolved bindings.
- Module migrations execute and leave schema in expected state.
- Module tests (or targeted fallback test filter) pass.
- If present, health checks report expected OK states for required checks.
- Tenant boundary and permission checks remain enforced on module endpoints.

### Traceability
- Route files:
  - `Modules/ZoneManagement/Routes/api/v1/api.php`
  - `Modules/ZoneManagement/Routes/web.php`
- Migration files:
  - `Modules/ZoneManagement/Database/Migrations/2022_03_05_085155_create_zones_table.php`
  - `Modules/ZoneManagement/Database/Migrations/2026_03_02_090300_add_company_id_to_zonemanagement_tables.php`
  - `Modules/ZoneManagement/Database/Migrations/2026_04_10_000001_add_geofence_fields_to_zones_table.php`
  - `Modules/ZoneManagement/Database/Migrations/2026_04_10_000002_create_zone_check_ins_table.php`
  - `Modules/ZoneManagement/Database/Migrations/2026_04_10_000003_create_cleaner_locations_table.php`
  - `Modules/ZoneManagement/Database/Migrations/2026_04_10_000004_create_route_points_table.php`
  - `Modules/ZoneManagement/Database/Migrations/2026_04_10_000005_create_gps_settings_table.php`
  - `Modules/ZoneManagement/Database/Migrations/2026_04_10_600001_add_cleaning_fields_to_zones_table.php`
  - `Modules/ZoneManagement/Database/Migrations/2026_04_10_600002_create_zone_pricing_table.php`
  - `Modules/ZoneManagement/Database/Migrations/2026_04_10_600003_create_zone_providers_table.php`

