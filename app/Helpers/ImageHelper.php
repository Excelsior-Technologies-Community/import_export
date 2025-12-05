<?php

if (!function_exists('getImageUrl')) {
    function getImageUrl($path)
    {
        if (!$path) {
            return asset('images/default-product.jpg');
        }
        
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        return asset('storage/' . $path);
    }
}