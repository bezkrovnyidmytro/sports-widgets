<?php

namespace DmytroBezkrovnyi\SportsWidgets\Model;

abstract class AbstractModel
{
	public static string $dbTableName;
	public static string $optionName;

	abstract public static function createDatabaseTable();

	abstract public static function saveDataFromCore();

	abstract public static function getAllData();

	abstract public static function getSelected();

	abstract public static function getSelectedData();

	abstract public static function getSortedBySelection();

	public static function dropDatabaseTable()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . static::$dbTableName;
		return $wpdb->query("DROP TABLE IF EXISTS $table_name");
	}

	public static function truncateDatabaseTable()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . static::$dbTableName;
		return $wpdb->query("TRUNCATE TABLE $table_name");
	}
}
