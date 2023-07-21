<?php

namespace DmytroBezkrovnyi\SportsWidgets\Addons;

use DmytroBezkrovnyi\SportsWidgets\Endpoints\AbstractEndpoint;
use DmytroBezkrovnyi\SportsWidgets\Endpoints\GetCompetitionStandings;
use DmytroBezkrovnyi\SportsWidgets\Endpoints\GetEventsByCompetition;
use DmytroBezkrovnyi\SportsWidgets\Endpoints\GetEventsByDate;
use DmytroBezkrovnyi\SportsWidgets\Endpoints\GetOdds;
use DmytroBezkrovnyi\SportsWidgets\PluginLoader;
use DmytroBezkrovnyi\SportsWidgets\Request\Request;

class RestCacheAddon
{
    protected PluginLoader $loader;
    
    private string $pluginName = 'wp-rest-cache/wp-rest-cache.php';
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        $this->initHooks();
    }
    
    private function initHooks()
    {
        $this->loader->add_filter('wp_rest_cache/allowed_endpoints', $this, 'addEndpoints');
        $this->loader->add_filter('wp_rest_cache/allowed_request_methods', $this, 'addRequestMethods');
        $this->loader->add_filter('wp_rest_cache/timeout', $this, 'addTimeout', 10, 2);
    }
    
    public function addEndpoints($allowed_endpoints)
    {
        if (empty($allowed_endpoints[AbstractEndpoint::getEndpointsBaseUrl()])) {
            $allowed_endpoints[AbstractEndpoint::getEndpointsBaseUrl()] = [
                GetEventsByDate::getEndpointUrl(),
                GetEventsByCompetition::getEndpointUrl(),
                GetOdds::getEndpointUrl(),
                GetCompetitionStandings::getEndpointUrl()
            ];
        }
        
        return $allowed_endpoints;
    }
    
    public function addRequestMethods($allowed_request_methods)
    {
        $allowed_request_methods[] = 'GET';
        $allowed_request_methods[] = 'POST';
        
        return $allowed_request_methods;
    }
    
    public function addTimeout($timeout, $options)
    {
        if (! empty($options['uri']) && strpos($options['uri'], AbstractEndpoint::getEndpointsBaseUrl()) !== false) {
            return Request::$cacheLifeTime;
        }
        
        return $timeout;
    }
    
    public function adminNotices()
    {
        $currentScreen = get_current_screen();
        
        if (! is_admin() || strpos($currentScreen->id, 'vcsw_settings') === false) {
            return;
        }
        
        $message = __('<strong>Sports Widgets plugin says:</strong>', VCSW_DOMAIN);
        $message .= __('We noticed that "wp-rest-cache/wp-rest-cache.php" plugin is not activated on your site.',
            VCSW_DOMAIN
        );
        $message .= __('We highly recommend to install this plugin <a href="https://wordpress.org/plugins/wp-rest-cache/" target="_blank" rel="noindex nofollow noreferrer">WP REST Cache</a> to speed up the loading of REST API requests.',
            VCSW_DOMAIN
        );
        printf('<div class="notice notice-warning"><p>%s</p></div>', $message);
    }
}
