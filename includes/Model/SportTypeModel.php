<?php

namespace DmytroBezkrovnyi\SportsWidgets\Model;

class SportTypeModel extends AbstractModel
{
    public static string $dbTableName = 'vcsw_sport_types';
    
    public static string $optionName = 'vcsw_selected_sport_types';
    
    public static function createDatabaseTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        
        $table_name = $wpdb->prefix . self::$dbTableName;
        
        $sql = "CREATE TABLE " . $table_name . " (
			id int(11) NOT NULL AUTO_INCREMENT,
			name tinytext NOT NULL,
			slug tinytext NOT NULL,
			PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        self::setDefaults();
    }
    
    private static function setDefaults()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$dbTableName;
        
        $defaultSportTypes = [
            [
                'name' => 'Football',
                'slug' => 'football',
            ],
            [
                'name' => 'Volleyball',
                'slug' => 'volleyball',
            ],
            [
                'name' => 'Basketball',
                'slug' => 'basketball',
            ],
        ];
        
        foreach ($defaultSportTypes as $default_sport_type) {
            $result = $wpdb->get_row("SELECT `name` FROM $table_name WHERE `name`='{$default_sport_type['name']}'");
            // If there are no such row - it will try and create the record.
            if (empty($result)) {
                $wpdb->insert($table_name, $default_sport_type);
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
    
    public static function getSelected() : array
    {
        return get_option(self::$optionName, self::getDefault()) ? : self::getDefault();
    }
    
    private static function getDefault() : array
    {
        return ['football'];
    }
    
    public static function saveDataFromCore() : bool
    {
        return true;
    }
    
    public static function getSelectedData() : bool
    {
        return true;
    }
    
    public static function getSortedBySelection() : bool
    {
        return true;
    }
}
