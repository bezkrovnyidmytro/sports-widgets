<?php

/**
 * Fired during plugin uninstallation
 *
 * @package    sports-widgets
 * @subpackage sports-widgets/includes
 */

namespace DmytroBezkrovnyi\SportsWidgets;

use DmytroBezkrovnyi\SportsWidgets\Model\BookmakerModel;
use DmytroBezkrovnyi\SportsWidgets\Model\CompetitionModel;
use DmytroBezkrovnyi\SportsWidgets\Model\MarketModel;
use DmytroBezkrovnyi\SportsWidgets\Model\SportTypeModel;

class PluginUninstaller
{
    public static function uninstall()
    {
        if (! current_user_can('activate_plugins')) {
            return;
        }
        
        // delete all options
        self::deleteAllOptions();
        
        // truncate all custom DB tables
        // incl wp_vcsw_competition, wp_vcsw_markets, wp_vcsw_sport_types
        self::dropAllCustomDbTables();
        
        self::deleteAllPluginPosts();
    }
    
    private static function deleteAllOptions() : void
    {
        global $wpdb;
        $query = "DELETE FROM `{$wpdb->prefix}options` WHERE `{$wpdb->prefix}options`.`option_name` LIKE '%vcsw%'";
        $wpdb->query($query);
    }
    
    private static function deleteAllPluginPosts()
    {
        // Use this method as a fallback option to remove whole Bookmaker CPT posts from database;
        BookmakerModel::deleteAllPosts();
    }
    
    private static function dropAllCustomDbTables()
    {
        CompetitionModel::dropDatabaseTable();
        MarketModel::dropDatabaseTable();
        SportTypeModel::dropDatabaseTable();
    }
}
