<?php

if (!function_exists('store_settings')) {
    function store_settings()
    {
        return \App\Models\StoreSetting::getActive();
    }
}

if (!function_exists('store_name')) {
    function store_name()
    {
        $store = \App\Models\StoreSetting::getActive();
        return $store ? $store->store_name : 'SkyraMart';
    }
}

if (!function_exists('store_logo')) {
    function store_logo()
    {
        $store = \App\Models\StoreSetting::getActive();
        return $store && $store->store_logo 
            ? asset('storage/' . $store->store_logo) 
            : asset('images/Skyra-L1.png');
    }
}