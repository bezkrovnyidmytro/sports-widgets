<?php

namespace DmytroBezkrovnyi\SportsWidgets\Model;

class StylesModel
{
    private static string $customStylesOption = 'vcsw_custom_styles';
    
    public static function getCustomStyles() : string
    {
        return get_option(self::$customStylesOption, self::getDefaults()) ? : self::getDefaults();
    }
    
    public static function setCustomStyles(string $styles = '') : string
    {
        if (! $styles) {
            return false;
        }
        
        return update_option(self::$customStylesOption, $styles);
    }
    
    private static function getDefaults() : string
    {
        return '';
    }
}
