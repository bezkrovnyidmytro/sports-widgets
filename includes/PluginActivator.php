<?php

/**
 * Fired during plugin activation
 *
 * @package    sports-widgets
 * @subpackage sports-widgets/includes
 */

namespace DmytroBezkrovnyi\SportsWidgets;

use DmytroBezkrovnyi\SportsWidgets\Model\CompetitionModel;
use DmytroBezkrovnyi\SportsWidgets\Model\MarketModel;
use DmytroBezkrovnyi\SportsWidgets\Model\SportTypeModel;
use DmytroBezkrovnyi\SportsWidgets\Request\Request;

class PluginActivator
{
	/**
	 * Activation goes here.
	 *
	 * @return void
	 */
	public static function activate()
	{
		if (! current_user_can('activate_plugins')) {
			return;
		}

		/**
		 * Check requirements before run the plugin.
		 */
		PluginRequirements::checkRequirements();

		self::createCustomDbTables();
		self::saveDefaultOptions();

		flush_rewrite_rules();
	}

	private static function createCustomDbTables()
	{
		CompetitionModel::createDatabaseTable();
		MarketModel::createDatabaseTable();
		SportTypeModel::createDatabaseTable();
	}

	private static function saveDefaultOptions()
	{
		if(empty(get_option(Request::$optionName))) {
			update_option(Request::$optionName, 'staging');
		}

		if(empty(get_option(PluginI18N::$optionName))) {
			update_option(PluginI18N::$optionName, 'en');
		}

		if(empty(get_option(SportTypeModel::$optionName))) {
			update_option(SportTypeModel::$optionName, ['football']);
		}
	}
}
