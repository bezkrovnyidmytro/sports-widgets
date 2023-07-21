<?php

namespace DmytroBezkrovnyi\SportsWidgets;

use DmytroBezkrovnyi\SportsWidgets\CPT\BookmakerCPT;

class Helpers
{
    public static string $frontEnvTypeFileName = '.wp.env';
    
    public static function allowUploadSvgCallback($mimes)
    {
        $mimes['svg'] = 'image/svg+xml';
        
        return $mimes;
    }
    
    public static function addFeaturedImageSupport()
    {
        add_theme_support('post-thumbnails', [BookmakerCPT::$postType]);
    }
    
    public static function isProductionEnv() : bool
    {
        $envType = self::getEnvironmentType();
        
        return ! empty($envType) && $envType === 'production';
    }
    
    public static function getEnvironmentType() : string
    {
        $frontendWpEnv = 'production';
        
        if (file_exists(VCSW_FRONTEND_DIR_PATH . self::$frontEnvTypeFileName)) {
            $frontendWpEnv = trim(file_get_contents(VCSW_FRONTEND_DIR_PATH . self::$frontEnvTypeFileName));
        }
        
        if (empty($frontendWpEnv)
            || ! in_array($frontendWpEnv, ['development', 'production'], true)
        ) {
            $frontendWpEnv = 'production';
        }
        
        return $frontendWpEnv;
    }
}
