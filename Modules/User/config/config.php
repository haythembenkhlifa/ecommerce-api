<?php

return [
    'name' => 'User',
    'users_per_page' => env('USER_MODULE_USERS_PER_PAGE', 100), // Always clear cache when updating this config
    'user_details_cache_life_time' =>  env('USER_MODULE_USER_DETAILS_CACHE_LIFE_TIME', 60 * 24), // Always clear cache when updating this config
];
