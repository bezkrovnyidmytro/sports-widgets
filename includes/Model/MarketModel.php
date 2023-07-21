<?php

namespace DmytroBezkrovnyi\SportsWidgets\Model;

class MarketModel extends AbstractModel
{
    public static string $dbTableName = 'vcsw_markets';
    
    public static string $optionName = 'vcsw_selected_markets';
    
    /**
     * @return void
     */
    public static function createDatabaseTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . self::$dbTableName;
        
        $sql = "CREATE TABLE " . $table_name . " (
			id int(11) NOT NULL AUTO_INCREMENT,
			_id tinytext NOT NULL,
			name tinytext NOT NULL,
			slug tinytext NOT NULL,
			sport_type tinytext NOT NULL,
			order_market tinytext NOT NULL,
			PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    public static function saveDataFromCore(array $markets = [])
    {
        if (empty($markets)) {
            return;
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . self::$dbTableName;
        
        foreach ($markets as $market) {
            $dataToUpdate = [
                '_id'          => $market['slug'],
                'name'         => $market['name'],
                'slug'         => $market['slug'],
                'sport_type'   => $market['sport_type'],
                'order_market' => '',
            ];
            
            $result = $wpdb->get_row("SELECT * FROM $table_name WHERE `_id`='{$dataToUpdate['_id']}';", ARRAY_A);
            
            if (empty($result)) {
                $wpdb->insert($table_name, $dataToUpdate);
            }
            else {
                $wpdb->update(
                    $table_name,
                    $dataToUpdate,
                    [
                        '_id' => $dataToUpdate['_id']
                    ]
                );
            }
        }
    }
    
    public static function getAllData()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$dbTableName;
        $results    = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
        
        if ($wpdb->last_error) {
            return [];
        }
        
        return $results;
    }
    
    public static function getSelected()
    {
        return get_option(self::$optionName) ? : [];
    }
    
    public static function truncateDatabaseTable()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$dbTableName;
        
        return $wpdb->query("TRUNCATE TABLE $table_name");
    }
    
    public static function getSelectedData() : array
    {
        $selectedMarkets = self::getSelected();
        
        if (empty($selectedMarkets)) {
            return [];
        }
        
        $selectedMarketsCore = [];
        
        global $wpdb;
        $table_name = $wpdb->prefix . self::$dbTableName;
        
        foreach ($selectedMarkets as $marketId) {
            $result = $wpdb->get_row("SELECT `_id`, `name`, `sport_type` FROM $table_name WHERE `_id`='$marketId';",
                ARRAY_A
            );
            
            if (empty($result)) {
                continue;
            }
            
            $selectedMarketsCore [] = $result;
        }
        
        return $selectedMarketsCore;
    }
    
    public static function getSortedBySelection() : bool
    {
        // Implement getSortedBySelection() method if needed.
        return true;
    }
}
