<?php

/**
 * Fired during plugin deactivation
 *
 * @package    sports-widgets
 * @subpackage sports-widgets/includes
 */

namespace DmytroBezkrovnyi\SportsWidgets;

use DmytroBezkrovnyi\SportsWidgets\CPT\BookmakerCPT;
use DmytroBezkrovnyi\SportsWidgets\Taxonomy\GeoLocationTaxonomy;

class PluginDeactivator
{
	public static function deactivate()
	{
		// de-activation goes here
		if (! current_user_can('activate_plugins')) {
			return;
		}

		unregister_post_type(BookmakerCPT::$postType);
		unregister_taxonomy(GeoLocationTaxonomy::$slug);

		flush_rewrite_rules();
	}
}
