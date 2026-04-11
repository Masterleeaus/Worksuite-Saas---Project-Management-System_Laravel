<?php

/**
 * Module migration dependency ordering.
 *
 * When installing or migrating modules, they must be run in this order
 * if multiple modules share a dependency chain.
 *
 * Format:  'ModuleName' => ['dependency1', 'dependency2']
 *
 * The orchestrator (module:migrate-ordered) reads this and ensures
 * dependencies are migrated before dependents.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Module Dependencies
    |--------------------------------------------------------------------------
    */

    'dependencies' => [

        // FSM stack — FSMCore MUST run first; all others depend on its tables
        // (fsm_stages, fsm_territories, fsm_locations, fsm_teams, fsm_tags,
        //  fsm_equipment, fsm_templates, fsm_orders)
        'FSMRecurring'         => ['FSMCore'],
        'FSMRoute'             => ['FSMCore'],
        'FSMVehicle'           => ['FSMCore'],
        'FSMServiceAgreement'  => ['FSMCore'],
        'FSMSkill'             => ['FSMCore'],
        'FSMActivity'          => ['FSMCore'],
        'FSMEquipment'         => ['FSMCore'],
        'FSMTimesheet'         => ['FSMCore'],
        'FSMAvailability'      => ['FSMCore'],
        'FSMStock'             => ['FSMCore'],
        'FSMCRM'               => ['FSMCore'],         // references fsm_locations, fsm_orders
        // FSM extension modules
        'FSMAccount'           => ['FSMCore'],
        'FSMKanban'            => ['FSMCore'],
        'FSMProject'           => ['FSMCore'],
        'FSMRepair'            => ['FSMCore'],
        'FSMRepairTemplate'    => ['FSMCore', 'FSMRepair'],
        'FSMSize'              => ['FSMCore'],
        'FSMStageAction'       => ['FSMCore'],
        'FSMCalendar'          => ['FSMCore'],
        // FSM client & billing modules
        'FSMPortal'            => ['FSMCore'],
        'FSMSales'             => ['FSMCore'],

        // Accounting stack (Accountings is the single accounting module — no sub-dependency)
        // 'Accountings' has no Laravel module dependency; it is standalone.

        // Titan stack
        'TitanAgents'          => ['TitanCore'],
        'TitanDocs'            => ['TitanCore'],
        'TitanHello'           => ['TitanCore'],
        'TitanZero'            => ['TitanCore'],
        'TitanPWA'             => ['TitanCore'],
        'TitanReach'           => ['TitanCore'],
        'TitanVault'           => ['TitanCore'],

        // Business modules that need GlobalSetting seeded first
        'Payroll'              => ['GlobalSetting'],
        'Zoom'                 => ['GlobalSetting'],
        'Subdomain'            => ['GlobalSetting'],
        'Affiliate'            => ['GlobalSetting'],
        'BookingModule'        => ['GlobalSetting'],
        'PaymentModule'        => ['GlobalSetting'],
        'BusinessSettingsModule' => ['GlobalSetting'],
        'ServiceManagement'    => ['GlobalSetting'],
        'ServicemanModule'     => ['GlobalSetting'],
        'ProviderManagement'   => ['GlobalSetting'],
        'PromotionManagement'  => ['GlobalSetting'],

        // QualityControl re-uses Inspection tables — Inspection must run first
        'QualityControl'       => ['Inspection'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Explicit migration order (top → bottom = first migrated)
    |--------------------------------------------------------------------------
    | Modules not listed here are migrated after all listed modules,
    | in alphabetical order.
    */

    'order' => [
        'GlobalSetting',
        'Accountings',          // was incorrectly 'Accounting' — only Accountings module exists
        'Inspection',
        'FSMCore',
        'FSMRecurring',
        'FSMRoute',
        'FSMVehicle',
        'FSMServiceAgreement',
        'FSMSkill',
        'FSMActivity',
        'FSMEquipment',
        'FSMTimesheet',
        'FSMAvailability',
        'FSMStock',
        'FSMCRM',
        'FSMCalendar',
        'FSMAccount',
        'FSMKanban',
        'FSMProject',
        'FSMRepair',
        'FSMRepairTemplate',
        'FSMSize',
        'FSMStageAction',
        'FSMPortal',
        'FSMSales',
        'TitanCore',
        'TitanAgents',
        'TitanDocs',
        'TitanHello',
        'TitanZero',
        'TitanPWA',
        'TitanReach',
        'TitanVault',
        'QualityControl',
        'Payroll',
        'Zoom',
        'Subdomain',
        'BookingModule',
        'PaymentModule',
        'BusinessSettingsModule',
        'Affiliate',
        'ServiceManagement',
        'ServicemanModule',
        'ProviderManagement',
        'PromotionManagement',
    ],

];
