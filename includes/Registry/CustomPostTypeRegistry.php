<?php

namespace DmytroBezkrovnyi\SportsWidgets\Registry;

use DmytroBezkrovnyi\SportsWidgets\CPT\BookmakerCPT;
use DmytroBezkrovnyi\SportsWidgets\PluginLoader;

class CustomPostTypeRegistry
{
    protected PluginLoader $loader;
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        
        $this->initHooks();
    }
    
    private function initHooks()
    {
        $this->loader->add_action('init', $this, 'initCustomPostTypes');
        $this->loader->add_action('template_redirect', $this, 'bookmakersRedirectToHome');
        $this->loader->add_filter('post_type_labels_' . BookmakerCPT::$postType, $this, 'setBookmakerLabels');
    }
    
    public function initCustomPostTypes()
    {
        new BookmakerCPT();
    }
    
    public function bookmakersRedirectToHome()
    {
        if (is_singular([BookmakerCPT::$postType])
            || strpos($_SERVER['REQUEST_URI'], BookmakerCPT::$postType) !== false) {
            wp_redirect(home_url('/'), 301);
            exit;
        }
        
        return true;
    }
    
    public static function setBookmakerLabels($labels)
    {
        return BookmakerCPT::setBookmakerLabels($labels);
    }
}
