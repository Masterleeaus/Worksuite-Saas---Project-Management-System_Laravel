<?php

return [
    'name' => 'EvidenceVault',

    /*
    |--------------------------------------------------------------------------
    | Feature flags
    |--------------------------------------------------------------------------
    */

    // Require at least one photo before a job can be marked complete.
    'require_photo_on_completion' => true,

    // Require a client signature (or locked-site photo) on completion.
    'require_signature_on_completion' => false,

    // Maximum file size in kilobytes for each uploaded photo.
    'max_photo_kb' => 10240,

    // Allowed MIME types for photo uploads.
    'allowed_photo_types' => ['image/jpeg', 'image/png', 'image/webp'],

    // Storage disk to use ('local', 's3', etc.).  Defaults to the app default.
    'storage_disk' => env('EVIDENCE_VAULT_DISK', 'local'),

    // Base path within the storage disk.
    'storage_path' => 'evidence-vault',
];
