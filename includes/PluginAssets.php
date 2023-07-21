<?php

namespace DmytroBezkrovnyi\SportsWidgets;

use DmytroBezkrovnyi\SportsWidgets\Block\LeagueStandings\LeagueStandingsBlock;
use DmytroBezkrovnyi\SportsWidgets\Block\SportsFixtures\SportsFixturesBlock;
use DmytroBezkrovnyi\SportsWidgets\Service\CustomizationService;
use DmytroBezkrovnyi\SportsWidgets\Shortcode\LeagueStandingsShortcode;
use DmytroBezkrovnyi\SportsWidgets\Shortcode\SportsFixturesShortcode;

class PluginAssets
{
    public static string $pluginAssetsHandleName;
    
    protected PluginLoader $loader;
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        
        self::$pluginAssetsHandleName = is_admin() ? 'vcsw-admin' : 'vcsw-public';
        
        $this->initHooks();
    }
    
    private function initHooks() : void
    {
        if (is_admin()) {
            $this->enqAdminAssets();
        }
        else {
            $this->enqPublicAssets();
        }
        
        $this->enqCustomizationAssets();
        $this->developmentHooks();
        $this->miscellaneousHooks();
    }
    
    private function enqAdminAssets() : void
    {
        new PluginAdmin($this->loader, self::$pluginAssetsHandleName);
    }
    
    private function enqPublicAssets() : void
    {
        new PluginPublic($this->loader);
    }
    
    private function enqCustomizationAssets() : void
    {
        new CustomizationService($this->loader);
    }
    
    private function developmentHooks() : void
    {
        if (Helpers::isProductionEnv()) {
            return;
        }
        
        $this->loader->add_action('wp_head', $this, 'getDevelopmentEnvAssetsEnq');
        $this->loader->add_action('admin_head', $this, 'getDevelopmentEnvAssetsEnq');
    }
    
    private function miscellaneousHooks() : void
    {
        $this->loader->add_filter('script_loader_tag', $this, 'addScriptLoaderTag', 0, 3);
    }
    
    public static function isAssetsEnqNeeded() : bool
    {
        global $post;
        
        return has_block(SportsFixturesBlock::getFullBlockName(), $post)
               || has_block(LeagueStandingsBlock::getFullBlockName(), $post)
               || has_shortcode($post->post_content, SportsFixturesShortcode::getShortcodeName())
               || has_shortcode($post->post_content, LeagueStandingsShortcode::getShortcodeName());
    }
    
    public function addScriptLoaderTag(string $tag, string $handle, string $src) : string
    {
        if ($tag && strpos($handle, 'vcsw') !== false) {
            return '<script type="module" src="' . esc_url($src) . '" id="' . $handle . '"></script>';
        }
        
        return $tag;
    }
    
    public function getDevelopmentEnvAssetsEnq() : void
    {
        ob_start();
        ?>
        <script type="module">
            import RefreshRuntime from 'http://localhost:5173/@react-refresh'

            RefreshRuntime.injectIntoGlobalHook(window)
            window.$RefreshReg$ = () => {
            }
            window.$RefreshSig$ = () => (type) => type
            window.__vite_plugin_react_preamble_installed__ = true
        </script>
        <script type="module" src="http://localhost:5173/@vite/client"></script>
        <script type="module" src="http://localhost:5173/src/widgets/Fixtures/fixtures-widget.js"></script>
        <script type="module"
                src="http://localhost:5173/src/widgets/LeagueStandings/league-standings-widget.js"></script>
        <script type="module" src="http://localhost:5173/view.js"></script>
        <?php
        echo ob_get_clean();
    }
}
