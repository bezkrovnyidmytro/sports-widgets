<?php

namespace DmytroBezkrovnyi\SportsWidgets\Registry;

use DmytroBezkrovnyi\SportsWidgets\Block\LeagueStandings\LeagueStandingsBlock;
use DmytroBezkrovnyi\SportsWidgets\Block\SportsFixtures\SportsFixturesBlock;
use DmytroBezkrovnyi\SportsWidgets\PluginLoader;

class BlockRegistry
{
    protected PluginLoader $loader;
    
    public static string $blockNamePrefix = 'sports-widgets/';
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        $this->initHooks();
    }
    
    private function initHooks()
    {
        $this->loader->add_action('init', $this, 'registerBlocks');
        $this->loader->add_filter('block_categories_all', $this, 'registerBlockCategory');
    }
    
    /**
     * Registers Gutenberg block
     */
    public function registerBlocks()
    {
        new SportsFixturesBlock();
        new LeagueStandingsBlock();
    }
    
    /**
     * Register category for custom Gutenberg blocks
     */
    public function registerBlockCategory($categories) : array
    {
        return array_merge(
            $categories,
            [
                [
                    'slug' => 'sports_widgets',
                    'title' => __('Sports Widgets', VCSW_DOMAIN),
                ],
            ]
        );
    }
}
