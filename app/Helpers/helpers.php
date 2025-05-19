<?php

use Illuminate\Support\Facades\Cache;

if (!function_exists('clearAppCache')) {
    function clearAppCache()
    {
        Cache::forget('categories');
        Cache::forget('categories_backend');
        Cache::forget('subcategories');
        Cache::forget('roles');
        Cache::forget('roles_without_client');
    }
}
