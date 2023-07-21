<?php

namespace DmytroBezkrovnyi\SportsWidgets\Customization;

use DmytroBezkrovnyi\SportsWidgets\Model\FontsModel;
use DmytroBezkrovnyi\SportsWidgets\PluginAssets;

class FontsCustomizer
{
    private const FONTS_API_URL = 'https://fonts.googleapis.com/css2';
    
    private string $customFont;
    
    private string $fontWeight;
    
    public function __construct()
    {
        $this->customFont = FontsModel::getCustomFont();
        $this->fontWeight = '400;700';
    }
    
    /**
     * @return string
     */
    public function getCustomFont() : string
    {
        return $this->customFont;
    }
    
    public function enqueueCustomFont() : void
    {
        if (empty($this->customFont)) {
            return;
        }
        
        $fontCdnSrc = $this->getFontCdnSrc();
        
        wp_enqueue_style(
            PluginAssets::$pluginAssetsHandleName . '-customizer-custom-font',
            $fontCdnSrc,
            [],
            null
        );
    }
    
    private function getFontCdnSrc() : string
    {
        return add_query_arg(['family' => $this->getFontFamily(), 'display' => 'swap'], self::FONTS_API_URL);
    }
    
    private function getFontFamily() : string
    {
        return sprintf('%s:wght@%s', urlencode($this->customFont), $this->fontWeight);
    }
    
    public function getCdnPreConnectTags() : string
    {
        return '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    }
    
    /**
     * @return string
     */
    public function getFontWeight() : string
    {
        return $this->fontWeight;
    }
}
