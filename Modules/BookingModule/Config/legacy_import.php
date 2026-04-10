<?php

return [
    // Legacy table name to detect.
    'table' => 'wo_service_appointments',

    // Marker prefix stored in schedules.unique_id for imported rows.
    'unique_prefix' => 'legacy-wo-',

    // Default created_by for imported rows when unknown.
    'default_created_by' => 1,
];
