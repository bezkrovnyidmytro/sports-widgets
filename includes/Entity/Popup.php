<?php

namespace DmytroBezkrovnyi\SportsWidgets\Entity;

class Popup
{
    public static string $disclaimerOptionName = 'vcsw_popup_disclaimer_text';
    
    public static string $logosOptionName = 'vcsw_popup_logos';
    
    public static string $popupBtnTextOptionName = 'vcsw_popup_btn_text';
    
    public static string $defaultMarketOptionName = 'vcsw_default_market';
    
    public static string $defaultMarket = '1X2';
    
    public static string $defaultPopupOpenerLabel = 'Bet now';
    
    public static function getSettings() : array
    {
        return [
            'opener_text'     => self::getButtonOpenerText(),
            'default_market'  => self::getDefaultMarket(),
            'disclaimer_text' => self::getDisclaimerText(),
            'images'          => self::getImages(),
        ];
    }
    
    public static function getButtonOpenerText() : string
    {
        return get_option(Popup::$popupBtnTextOptionName) ? : __(self::$defaultPopupOpenerLabel, VCSW_DOMAIN);
    }
    
    public static function getDefaultMarket() : string
    {
        return get_option(Popup::$defaultMarketOptionName) ? : self::$defaultMarket;
    }
    
    public static function setDefaultMarket(string $market = '') : string
    {
        if (! $market) {
            $market = self::$defaultMarket;
        }
        
        return update_option(Popup::$defaultMarketOptionName, $market);
    }
    
    public static function getDisclaimerText() : string
    {
        return get_option(Popup::$disclaimerOptionName) ? : '';
    }
    
    public static function getImages() : array
    {
        return get_option(Popup::$logosOptionName) ? : [];
    }
    
    public static function setButtonOpenerText(string $text = '') : string
    {
        if (! $text) {
            $text = __(self::$defaultPopupOpenerLabel, VCSW_DOMAIN);
        }
        
        return update_option(Popup::$popupBtnTextOptionName, $text);
    }
    
    public static function setDisclaimerText(string $text = '') : string
    {
        return update_option(Popup::$disclaimerOptionName, $text);
    }
    
    public static function setImages(array $logos = []) : string
    {
        return update_option(Popup::$logosOptionName, $logos);
    }
    
    public static function getBookmakerPlaceholderImage() : string
    {
        return '';
    }
}
