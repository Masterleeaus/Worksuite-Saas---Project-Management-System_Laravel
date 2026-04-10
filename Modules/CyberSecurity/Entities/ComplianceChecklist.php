<?php

namespace Modules\CyberSecurity\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComplianceChecklist extends BaseModel
{
    use HasFactory, HasCompany;

    protected $guarded = ['id'];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public static array $frameworkItems = [
        'gdpr' => [
            ['key' => 'lawful_basis', 'label' => 'Lawful basis for processing documented'],
            ['key' => 'privacy_notice', 'label' => 'Privacy notice published and up to date'],
            ['key' => 'consent_mechanism', 'label' => 'Consent mechanism in place where required'],
            ['key' => 'dsar_process', 'label' => 'Data Subject Access Request (DSAR) process defined'],
            ['key' => 'data_retention', 'label' => 'Data retention policy documented and enforced'],
            ['key' => 'breach_notification', 'label' => 'Breach notification process within 72h defined'],
            ['key' => 'dpa_agreements', 'label' => 'Data Processing Agreements with vendors signed'],
            ['key' => 'dpo_appointed', 'label' => 'DPO appointed or necessity assessed'],
            ['key' => 'ropa_maintained', 'label' => 'Record of Processing Activities (ROPA) maintained'],
            ['key' => 'encryption_at_rest', 'label' => 'Personal data encrypted at rest'],
            ['key' => 'encryption_in_transit', 'label' => 'Personal data encrypted in transit (HTTPS/TLS)'],
        ],
        'privacy_act_au' => [
            ['key' => 'app1_open_transparent', 'label' => 'APP 1 — Open and transparent management of personal information'],
            ['key' => 'app2_anonymity', 'label' => 'APP 2 — Anonymity and pseudonymity options offered where practicable'],
            ['key' => 'app3_collection', 'label' => 'APP 3 — Collection of solicited personal information lawful'],
            ['key' => 'app5_notice', 'label' => 'APP 5 — Notification of collection provided at time of collection'],
            ['key' => 'app6_use_disclosure', 'label' => 'APP 6 — Use or disclosure limited to primary purpose'],
            ['key' => 'app8_overseas', 'label' => 'APP 8 — Cross-border disclosure safeguards in place'],
            ['key' => 'app11_security', 'label' => 'APP 11 — Reasonable steps to protect personal information'],
            ['key' => 'app12_access', 'label' => 'APP 12 — Individual access to their personal information provided'],
            ['key' => 'app13_correction', 'label' => 'APP 13 — Correction of personal information process defined'],
            ['key' => 'notifiable_breach', 'label' => 'Notifiable Data Breaches (NDB) scheme procedures in place'],
            ['key' => 'pii_register', 'label' => 'Register of personal information holdings maintained'],
        ],
    ];
}
