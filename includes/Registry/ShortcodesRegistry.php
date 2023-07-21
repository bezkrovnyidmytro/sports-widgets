<?php

namespace DmytroBezkrovnyi\SportsWidgets\Registry;

use DmytroBezkrovnyi\SportsWidgets\PluginLoader;
use DmytroBezkrovnyi\SportsWidgets\Shortcode\LeagueStandingsShortcode;
use DmytroBezkrovnyi\SportsWidgets\Shortcode\SportsFixturesShortcode;

class ShortcodesRegistry
{
    protected PluginLoader $loader;

    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;

        $this->initHooks();
    }

    private function initHooks()
    {
        $this->loader->add_action('init', $this, 'shortcodesRegistry');
    }

	public function shortcodesRegistry()
	{
		new SportsFixturesShortcode();
		new LeagueStandingsShortcode();
	}
}
