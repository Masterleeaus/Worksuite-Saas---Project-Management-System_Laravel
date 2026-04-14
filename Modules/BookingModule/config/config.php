<?php

return [
    'name' => 'BookingModule',
    'parent_envato_id' => env('BOOKINGMODULE_PARENT_ENVATO_ID', 23263417),
    'parent_product_name' => env('BOOKINGMODULE_PARENT_PRODUCT_NAME', 'worksuite-saas-new'),
    'parent_min_version' => env('BOOKINGMODULE_PARENT_MIN_VERSION', '5.0.0'),
    'setting' => \Modules\BookingModule\Entities\BookingModuleSetting::class,
    'booking_pages' => [
        'default_status' => 'draft',
        'request_notification_email' => env('BOOKINGMODULE_REQUEST_NOTIFICATION_EMAIL'),
    ],
];
