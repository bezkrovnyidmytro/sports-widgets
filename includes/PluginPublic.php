<?php

/**
 * The public-facing functionality of the plugin.
 * @package    sports-widgets
 * @subpackage sports-widgets/includes
 */

namespace DmytroBezkrovnyi\SportsWidgets;

use DmytroBezkrovnyi\SportsWidgets\Block\LeagueStandings\LeagueStandingsBlock;
use DmytroBezkrovnyi\SportsWidgets\Block\SportsFixtures\SportsFixturesBlock;
use DmytroBezkrovnyi\SportsWidgets\Shortcode\LeagueStandingsShortcode;
use DmytroBezkrovnyi\SportsWidgets\Shortcode\SportsFixturesShortcode;

class PluginPublic
{
    protected PluginLoader $loader;
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        
        $this->initHooks();
    }
    
    private function initHooks()
    {
        $this->loader->add_action('wp_enqueue_scripts', $this, 'enqPluginAssets');
    }
    
    public function enqPluginAssets()
    {
        if (! Helpers::isProductionEnv()) {
            return;
        }
        
        global $post;
        
        $isDepsNeeded = false;
        $depsHandle   = '';
        
        if (
            file_exists(VCSW_FRONTEND_BUILD_DIR_PATH . 'fixtures-widget.js')
            && (
                has_block(SportsFixturesBlock::getFullBlockName(), $post)
                || has_shortcode($post->post_content, SportsFixturesShortcode::getShortcodeName())
            )
        ) {
            wp_enqueue_script(
                SportsFixturesBlock::getBlockInternalHandleName(),
                VCSW_FRONTEND_BUILD_URL_PATH . 'fixtures-widget.js',
                [],
                filemtime(VCSW_FRONTEND_BUILD_DIR_PATH . 'fixtures-widget.js'),
                true
            );
            
            $isDepsNeeded = true;
            $depsHandle   = SportsFixturesBlock::getBlockInternalHandleName();
        }
        
        if (
            file_exists(VCSW_FRONTEND_BUILD_DIR_PATH . 'league-standings-widget.js')
            && (
                has_block(LeagueStandingsBlock::getFullBlockName(), $post)
                || has_shortcode($post->post_content, LeagueStandingsShortcode::getShortcodeName())
            )
        ) {
            wp_enqueue_script(
                LeagueStandingsBlock::getBlockInternalHandleName(),
                VCSW_FRONTEND_BUILD_URL_PATH . 'league-standings-widget.js',
                [],
                filemtime(VCSW_FRONTEND_BUILD_DIR_PATH . 'league-standings-widget.js'),
                true
            );
            
            $isDepsNeeded = true;
            $depsHandle   = LeagueStandingsBlock::getBlockInternalHandleName();
        }
        
        if ($isDepsNeeded && $depsHandle) {
            wp_enqueue_script(
                'vcsw-initializer',
                VCSW_FRONTEND_BUILD_URL_PATH . 'view.js',
                [$depsHandle],
                filemtime(VCSW_FRONTEND_BUILD_DIR_PATH . 'view.js'),
                true
            );
        }
    }
}
