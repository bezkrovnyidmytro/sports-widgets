<?php

namespace DmytroBezkrovnyi\SportsWidgets\Model;

class FontsModel
{
    private static string $customFontOption = 'vcsw_custom_font_family';
    
    private static function getDefaults() : string
    {
        return '';
    }
    
    public static function getAllCdnFonts() : array
    {
        $fonts = [
            'Source Sans Pro',
            'Open Sans',
            'Oswald',
            'Playfair Display',
            'Montserrat',
            'Raleway',
            'Droid Sans',
            'Lato',
            'Arvo',
            'Merriweather',
            'Oxygen',
            'PT Serif',
            'PT Sans',
            'PT Sans Narrow',
            'Cabin',
            'Fjalla One',
            'Francois One',
            'Josefin Sans',
            'Libre Baskerville',
            'Arimo',
            'Ubuntu',
            'Bitter',
            'Droid Serif',
            'Roboto',
            'Open Sans Condensed',
            'Roboto Condensed',
            'Roboto Slab',
            'Yanone Kaffeesatz',
            'Rokkitt',
        ];
        
        sort($fonts);
        
        return $fonts;
    }
    
    public static function getCustomFont() : string
    {
        return get_option(self::$customFontOption, self::getDefaults()) ? : self::getDefaults();
    }
    
    public static function setCustomFont(string $font = '') : string
    {
        return update_option(self::$customFontOption, $font);
    }
}
