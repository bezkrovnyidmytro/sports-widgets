<?php

namespace DmytroBezkrovnyi\SportsWidgets\Model;

use mysqli_result;

/**
 *
 */
class CompetitionModel extends AbstractModel
{
    public static string $dbTableName = 'vcsw_competition';
    
    public static string $optionName = 'vcsw_selected_competitions';
    
    /**
     * @return void
     */
    public static function createDatabaseTable()
    {
        global $wpdb;
        $charsetCollate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $tableName = $wpdb->prefix . self::$dbTableName;
        
        $sqlQuery = "CREATE TABLE $tableName (
			id int(11) NOT NULL AUTO_INCREMENT,
			_id tinytext NOT NULL,
			competition tinytext NOT NULL,
			country tinytext NOT NULL,
			season tinytext NOT NULL,
			sports_type  tinytext NOT NULL,
			start_date datetime DEFAULT '2023-01-01 00:00:00' NOT NULL,
			end_date datetime DEFAULT '2023-01-01 00:00:00' NOT NULL,
			PRIMARY KEY (id)
        ) $charsetCollate;";
        
        dbDelta($sqlQuery);
        
        $sqlQuery = "ALTER TABLE $tableName ADD content VARCHAR(550) NULL DEFAULT NULL AFTER sports_type";
        maybe_add_column($tableName, 'content', $sqlQuery);
    }
    
    public static function saveDataFromCore(array $competitions = [])
    {
        if (empty($competitions)) {
            return;
        }
        
        global $wpdb;
        $tableName = $wpdb->prefix . self::$dbTableName;
        
        foreach ($competitions as $competition) {
            $dataToUpdate = [
                '_id' => $competition['_id'],
                'competition' => $competition['competition'],
                'country' => $competition['country'],
                'season' => $competition['season'],
                'sports_type' => $competition['sports_type'],
                'start_date' => $competition['start_date'],
                'end_date' => $competition['end_date'],
            ];
            
            $result = $wpdb->get_row("SELECT * FROM $tableName WHERE `_id`='{$dataToUpdate['_id']}';", ARRAY_A);
            
            if (empty($result)) {
                $wpdb->insert($tableName, $dataToUpdate);
            }
            else {
                $wpdb->update(
                    $tableName,
                    $dataToUpdate,
                    [
                        '_id' => $dataToUpdate['_id']
                    ]
                );
            }
        }
    }
    
    /**
     * @return bool|int|mysqli_result|resource|null
     */
    public static function truncateDatabaseTable()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . self::$dbTableName;
        
        return $wpdb->query("TRUNCATE TABLE $tableName");
    }
    
    public static function getAllData() : array
    {
        global $wpdb;
        $tableName = $wpdb->prefix . self::$dbTableName;
        
        $query = "SELECT * FROM $tableName ORDER BY competition ASC";
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        if ($wpdb->last_error) {
            return [];
        }
        
        return $results;
    }
    
    public static function getSortedBySelection(array $allItems = [], array $selectedItems = []) : array
    {
        if (! $allItems) {
            return [];
        }
        
        if (! $selectedItems) {
            return $allItems;
        }
        
        $diff = array_udiff($allItems, $selectedItems, function ($a, $b) {
            return strcmp($a['_id'], $b['_id']);
        });
        
        return array_merge($selectedItems, $diff);
    }
    
    public static function getSelectedData() : array
    {
        $selectedCompetitions = self::getSelected();
        
        if (empty($selectedCompetitions)) {
            return [];
        }
        
        global $wpdb;
        $tableName = $wpdb->prefix . self::$dbTableName;
        
        $ret = [];
        
        foreach ($selectedCompetitions as $competitionId) {
            $result = $wpdb->get_row("SELECT * FROM $tableName WHERE _id='$competitionId'", ARRAY_A);
            
            if (! $result) {
                continue;
            }
            
            $result['selected'] = 1;
            
            $ret [] = $result;
        }
        
        return $ret;
    }
    
    public static function getSelected()
    {
        return get_option(self::$optionName, []) ? : [];
    }
    
    public static function updateCompetitionContent(string $compId = '', string $content = '') : bool
    {
        // $compId === competition id from the  API
        if (! $compId) {
            return false;
        }
        
        global $wpdb;
        $tableName = $wpdb->prefix . self::$dbTableName;
        
        $sqlQuery = "UPDATE $tableName SET content = '$content' WHERE $tableName._id = '$compId'";
        $result   = $wpdb->query($sqlQuery);
        
        return ! empty($result);
    }
    
    public static function getCompetitionContent(string $compId = '') : string
    {
        // $compId === competition id from the  API
        if (! $compId) {
            return false;
        }
        
        global $wpdb;
        $tableName = $wpdb->prefix . self::$dbTableName;
        
        $sqlQuery = "SELECT content FROM $tableName WHERE $tableName._id = '$compId'";
        $result   = $wpdb->get_var($sqlQuery);
        
        return ! empty($result) ? $result : '';
    }
}
