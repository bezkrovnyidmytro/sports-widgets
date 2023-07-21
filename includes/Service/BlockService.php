<?php

namespace DmytroBezkrovnyi\SportsWidgets\Service;

use DmytroBezkrovnyi\SportsWidgets\Endpoints\AbstractEndpoint;
use DmytroBezkrovnyi\SportsWidgets\Entity\Popup;
use DmytroBezkrovnyi\SportsWidgets\Model\BookmakerModel;
use DmytroBezkrovnyi\SportsWidgets\Model\CompetitionModel;
use DmytroBezkrovnyi\SportsWidgets\Model\MarketModel;
use DmytroBezkrovnyi\SportsWidgets\Model\SeasonsModel;
use DmytroBezkrovnyi\SportsWidgets\Model\SportTypeModel;
use DmytroBezkrovnyi\SportsWidgets\Model\TranslationModel;
use DmytroBezkrovnyi\SportsWidgets\PluginAssets;
use DmytroBezkrovnyi\SportsWidgets\PluginI18N;
use DmytroBezkrovnyi\SportsWidgets\PluginLoader;
use Exception;

class BlockService
{
    protected PluginLoader $loader;
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        
        $this->initHooks();
    }
    
    private function initHooks()
    {
        $this->loader->add_action('wp_enqueue_scripts', $this, 'outputCommonBlockArgsFrontend', 0);
        $this->loader->add_action('admin_enqueue_scripts', $this, 'outputCommonBlockArgsAdmin', 0);
    }
    
    /**
     * @throws Exception
     */
    public static function outputCommonBlockArgsFrontend() : void
    {
        if (! PluginAssets::isAssetsEnqNeeded()) {
            return;
        }
        
        echo self::outputCommonBlockArgs();
    }
    
    /**
     * @throws Exception
     */
    private static function outputCommonBlockArgs() : string
    {
        $blockArgs = self::getCommonBlockArgs();
        
        return self::getCommonBlockArgsHtml($blockArgs);
    }
    
    /**
     * @throws Exception
     */
    private static function getCommonBlockArgs() : array
    {
        return [
            'lang'           => PluginI18N::getSelectedLang(), // by default "en"
            'time_format'    => PluginI18N::getSiteDefaultTimeFormat(),
            'competitions'   => CompetitionModel::getSelectedData(),
            'bookmakers'     => BookmakerModel::getSelectedData(),
            'markets'        => MarketModel::getSelectedData(),
            'seasons'        => SeasonsModel::getAllSeasons(),
            'sports_types'   => SportTypeModel::getSelected(),
            'popup_settings' => Popup::getSettings(),
            'rest_url'       => get_rest_url(null, AbstractEndpoint::getEndpointsBaseUrl()),
            'locale_data'    => TranslationModel::getAllTranslations(),
        ];
    }
    
    private static function getCommonBlockArgsHtml(array $blockArgs = []) : string
    {
        if (empty($blockArgs)) {
            return '';
        }
        
        $blockArgsJson = json_encode($blockArgs);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return '';
        }
        
        return '<script type="application/json" id="vcsw--common-block-args">'
               . $blockArgsJson
               . '</script>';
    }
    
    /**
     * @throws Exception
     */
    public static function outputCommonBlockArgsAdmin() : void
    {
        echo self::outputCommonBlockArgs();
    }
}
