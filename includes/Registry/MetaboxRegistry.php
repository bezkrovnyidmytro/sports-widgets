<?php

namespace DmytroBezkrovnyi\SportsWidgets\Registry;

use DmytroBezkrovnyi\SportsWidgets\Entity\BookmakerMetabox;
use DmytroBezkrovnyi\SportsWidgets\PluginLoader;

class MetaboxRegistry
{
    protected PluginLoader $loader;
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        
        $this->initMetaboxes();
    }
    
    private function initMetaboxes()
    {
        new BookmakerMetabox($this->loader);
    }
}
