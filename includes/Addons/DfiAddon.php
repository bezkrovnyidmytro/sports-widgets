<?php

namespace DmytroBezkrovnyi\SportsWidgets\Addons;

use DmytroBezkrovnyi\SportsWidgets\CPT\BookmakerCPT;
use DmytroBezkrovnyi\SportsWidgets\PluginLoader;

class DfiAddon
{
    private PluginLoader $loader;
    
    private string $pluginName = 'default-featured-image/set-default-featured-image.php';
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        
        $this->initHooks();
    }
    
    private function initHooks()
    {
        $this->loader->add_filter('dfi_thumbnail_id', $this, 'excludeDefaultFeatImage', 10, 2);
    }
    
    /**
     * Exclude plugin assets from caching
     *
     * @param int $dfi      Default featured image ID
     * @param int $objectId Current object ID
     *
     * @return int
     */
    public function excludeDefaultFeatImage(int $dfi, int $objectId) : int
    {
        if (get_post_type($objectId) === BookmakerCPT::$postType) {
            return 0;
        }
        
        return $dfi;
    }
}
