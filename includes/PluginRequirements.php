<?php

namespace DmytroBezkrovnyi\SportsWidgets;

class PluginRequirements
{
    private static string $phpVersionRequired = '7.4';
    
    private static string $wpVersionRequired = '5.9';
    
    public static function checkRequirements()
    {
        if (! defined('WPINC')) {
            exit('This file is called directly.');
        }
        
        if (version_compare(PHP_VERSION, self::$phpVersionRequired, '<')) {
            exit('PHP ' . self::$phpVersionRequired . ' is required.');
        }
        
        global $wp_version;
        
        if (version_compare($wp_version, self::$wpVersionRequired, '<')) {
            exit('WP Core ' . self::$wpVersionRequired . ' is required.');
        }
        
        if (! function_exists('curl_init') || ! function_exists('curl_version')) {
            exit('CURL module is required.');
        }
        
        if (! class_exists('\Composer\Autoload\ClassLoader')) {
            exit('Composer autoloader is not installed.');
        }
    }
}
