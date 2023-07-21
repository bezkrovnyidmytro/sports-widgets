<?php

namespace DmytroBezkrovnyi\SportsWidgets\Addons;

use DmytroBezkrovnyi\SportsWidgets\PluginLoader;

class WpRocketAddon
{
    private PluginLoader $loader;
    
    private string $pluginName = 'wp-rocket/wp-rocket.php';
    
    private string $pathToAssets = '/wp-content/plugins/sports-widgets/frontend/build/(.*)\.js';
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        
        $this->initHooks();
    }
    
    private function initHooks()
    {
        $this->loader->add_filter('rocket_exclude_js', $this, 'excludePluginJs');
        $this->loader->add_filter('rocket_exclude_defer_js', $this, 'excludePluginDeferJs');
        $this->loader->add_filter('rocket_delay_js_exclusions', $this, 'excludePluginDelayJs');
    }
    
    /**
     * Exclude plugin assets from caching
     *
     * @param array $excludedFiles
     *
     * @return array
     */
    public function excludePluginJs(array $excludedFiles) : array
    {
        $excludedFiles [] = $this->pathToAssets;
        
        return $excludedFiles;
    }
    
    /**
     * Do not load plugin assets with "defer", because it has "async" attribute
     *
     * @param array $excludedFiles
     *
     * @return array
     */
    public function excludePluginDeferJs(array $excludedFiles) : array
    {
        $excludedFiles [] = $this->pathToAssets;
        
        return $excludedFiles;
    }
    
    /**
     * Exclude plugin assets from delaying enq
     *
     * @param array $excludedFiles
     *
     * @return array
     */
    public function excludePluginDelayJs(array $excludedFiles) : array
    {
        $excludedFiles [] = $this->pathToAssets;
        
        return $excludedFiles;
    }
}
