<?php

return [
    'name' => 'FSMAvailability',

    // Australian states/territories for public holiday import
    'au_states' => [
        'ACT' => 'Australian Capital Territory',
        'NSW' => 'New South Wales',
        'NT'  => 'Northern Territory',
        'QLD' => 'Queensland',
        'SA'  => 'South Australia',
        'TAS' => 'Tasmania',
        'VIC' => 'Victoria',
        'WA'  => 'Western Australia',
    ],

    // Default state for public holiday import
    'default_au_state' => 'VIC',

    // Nager.Date API base URL for public holiday data
    'holiday_api_url' => 'https://date.nager.at/api/v3/PublicHolidays/{year}/AU',
];
