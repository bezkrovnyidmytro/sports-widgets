<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    sports-widgets
 * @subpackage sports-widgets/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    sports-widgets
 * @subpackage sports-widgets/includes
 */

namespace DmytroBezkrovnyi\SportsWidgets;

use DmytroBezkrovnyi\SportsWidgets\Block\LeagueStandings\LeagueStandingsBlock;
use DmytroBezkrovnyi\SportsWidgets\Block\SportsFixtures\SportsFixturesBlock;
use DmytroBezkrovnyi\SportsWidgets\CPT\BookmakerCPT;

class PluginAdmin
{
    protected PluginLoader $loader;
    
    private string $pluginAssetsHandleName;
    
    public function __construct(PluginLoader $loader, string $pluginAssetsHandleName)
    {
        $this->loader = $loader;
        $this->pluginAssetsHandleName = $pluginAssetsHandleName . '-common';
        
        $this->initHooks();
    }
    
    private function initHooks()
    {
        $this->loader->add_action('admin_enqueue_scripts', $this, 'enqCommonAssets');
        $this->loader->add_action('admin_enqueue_scripts', $this, 'enqBookmakersCPTStyles');
        $this->loader->add_action('admin_enqueue_scripts', $this, 'enqPluginAssets');
        $this->loader->add_action('admin_enqueue_scripts', $this, 'enqStylesPageAssets');
    }
    
    /**
     * Enqueue scripts and styles from /frontend/build/manifest.json on the specific pages
     * i.g.: edit pages with Gutenberg editor, plugin pages with shortcode generator and styles settings
     *
     * @param string $hook
     *
     * @return void
     */
    public function enqPluginAssets(string $hook = '')
    {
        if (! Helpers::isProductionEnv()) {
            return;
        }
        
        if (
            in_array($hook, ['post-new.php', 'post.php']) // edit pages with Gutenberg editor
            || strpos($hook, 'vcsw_settings_shortcode_generator') !== false // plugin page with shortcode generator
            || strpos($hook, 'vcsw_settings_styles') !== false // plugin page with styles settings
        ) {
            
            wp_enqueue_script(
                SportsFixturesBlock::getBlockInternalHandleName(),
                VCSW_FRONTEND_BUILD_URL_PATH . 'fixtures-widget.js',
                [],
                filemtime(VCSW_FRONTEND_BUILD_DIR_PATH . 'fixtures-widget.js'),
                true
            );
            
            wp_enqueue_script(
                LeagueStandingsBlock::getBlockInternalHandleName(),
                VCSW_FRONTEND_BUILD_URL_PATH . 'league-standings-widget.js',
                [],
                filemtime(VCSW_FRONTEND_BUILD_DIR_PATH . 'league-standings-widget.js'),
                true
            );
        }
    }
    
    /**
     * Register the stylesheets and scripts for the admin area.
     *
     * @since    1.0.0
     */
    public function enqCommonAssets(string $hook = '')
    {
        if (empty($hook)
            || strpos($hook, 'vcsw') === false
            || ! file_exists(VCSW_DIR_PATH . 'build/admin/index.css')
            || ! file_exists(VCSW_DIR_PATH . 'build/admin/index.js')
        ) {
            return;
        }
        
        /**
         * Admin styles
         */
        wp_enqueue_style(
            $this->pluginAssetsHandleName,
            VCSW_URL_PATH . 'build/admin/index.css',
            [],
            filemtime(VCSW_DIR_PATH . 'build/admin/index.css')
        );
        /**
         * EOF Admin styles
         */
        
        /**
         * Admin scripts
         */
        
        // Enq editor and media scripts just on specific pages
        if (strpos($hook, 'vcsw_settings_odds_popup') !== false) {
            wp_enqueue_media();
        }
        
        if (strpos($hook, 'vcsw_settings_competitions') !== false) {
            wp_enqueue_editor();
        }
        
        wp_enqueue_script(
            $this->pluginAssetsHandleName,
            VCSW_URL_PATH . 'build/admin/index.js',
            [],
            filemtime(VCSW_DIR_PATH . 'build/admin/index.js'),
            true
        );
        
        wp_localize_script(
            $this->pluginAssetsHandleName,
            'vcsw_script_args',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
            ]
        );
        /**
         * EOF Admin scripts
         */
    }
    
    public function enqStylesPageAssets(string $hook = '')
    {
        if (strpos($hook, 'vcsw_settings_styles') === false
            || ! file_exists(VCSW_DIR_PATH . 'build/style-settings-admin/index.js')
        ) {
            return;
        }
        
        wp_enqueue_script(
            $this->pluginAssetsHandleName . '-styles-settings-page',
            VCSW_URL_PATH . 'build/style-settings-admin/index.js',
            [],
            filemtime(VCSW_DIR_PATH . 'build/style-settings-admin/index.js'),
            true
        );
    }
    
    public function enqBookmakersCPTStyles(string $hook = '')
    {
        global $post;
        
        if (empty($hook)
            || ! in_array($hook, ['post-new.php', 'post.php'])
            || ! file_exists(VCSW_DIR_PATH . 'build/bookmaker-admin/index.css')
            || BookmakerCPT::$postType !== $post->post_type
        ) {
            return;
        }
        
        wp_enqueue_style(
            $this->pluginAssetsHandleName . '-single-bookmaker-styles',
            VCSW_URL_PATH . 'build/bookmaker-admin/index.css',
            [],
            filemtime(VCSW_DIR_PATH . 'build/bookmaker-admin/index.css')
        );
    }
}
