<?php

use App\Models\SeoSetting;

if (!function_exists('getSeo')) {
    function getSeo($page)
    {
        //  Normalize the path
        $page = trim($page, '/');   // remove leading/trailing slash

        // Homepage handling
        if ($page === '') {
            $page = 'home';
        }
        if (str_starts_with($page, 'our-blogs/')) {
            return null;
        }

        //  Try to find exact match
        $seo = SeoSetting::where('page', $page)->first();

        if ($seo) {
            return $seo;
        }

        // Try matching the first segment (like for dynamic pages)
        $segment = explode('/', $page)[0];

        $seo = SeoSetting::where('page', $segment)->first();

        if ($seo) {
            return $seo;
        }

     
    }


}
