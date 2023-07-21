<?php

namespace DmytroBezkrovnyi\SportsWidgets\Model;

class SeasonsModel
{
    public static function getAllSeasons(): array
    {
        global $wpdb;
        $table_name = $wpdb->prefix . CompetitionModel::$dbTableName;
        $results = $wpdb->get_results("SELECT DISTINCT `$table_name`.`season` FROM `$table_name`;", ARRAY_A);

        if ($wpdb->last_error) {
            return [];
        }

        return ! empty($results) ? wp_list_pluck($results, 'season') : [];
    }
}
