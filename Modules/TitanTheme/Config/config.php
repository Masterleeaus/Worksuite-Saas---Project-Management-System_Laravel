<?php

return [
    'name' => 'TitanTheme',

    /*
    |--------------------------------------------------------------------------
    | Default Theme Settings
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'primary_color'    => '#4B6EF5',
        'secondary_color'  => '#6C757D',
        'accent_color'     => '#F0AD4E',
        'background_color' => '#F5F7FA',
        'text_color'       => '#343A40',
        'heading_font'     => 'Inter',
        'body_font'        => 'Inter',
        'sidebar_width'    => 260,
        'header_height'    => 64,
        'border_radius'    => 6,
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Google Fonts
    |--------------------------------------------------------------------------
    */
    'fonts' => [
        'Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat',
        'Poppins', 'Source Sans Pro', 'Raleway', 'Nunito', 'Ubuntu',
    ],

    /*
    |--------------------------------------------------------------------------
    | White-Label Storage
    |--------------------------------------------------------------------------
    */
    'storage_disk' => env('TITAN_THEME_DISK', 'public'),
    'storage_path' => 'titan-theme',

    /*
    |--------------------------------------------------------------------------
    | CSS Variable Prefix
    |--------------------------------------------------------------------------
    */
    'css_var_prefix' => '--tt-',
];
