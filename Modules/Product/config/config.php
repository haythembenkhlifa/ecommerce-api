<?php

return [
    'name' => 'Product',
    'products_per_page' => env('PRODUCT_MODULE_PRODUCTS_PER_PAGE', 100), // Always clear cache when updating this config
    'products_cache_life_time' =>  env('PRODUCT_MODULE_PRODUCTS_CACHE_LIFE_TIME', 60), // // Always clear cache when updating this config
    'products_image_disk' => env('PRODUCT_MODULE_PRODUCTS_IMAGES_DISK', 'public')
];
