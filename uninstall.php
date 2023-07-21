<?php

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

global $wpdb;

/**
 * Delete all plugin options.
 */
$query = "DELETE FROM `{$wpdb->prefix}options` WHERE `{$wpdb->prefix}options`.`option_name` LIKE '%vcsw%'";
$result = $wpdb->query($query);
/**
 * EOF Delete all plugin options.
 */

/**
 * Delete all Bookmakers CPT posts & postmeta & terms.
 */
$query = "DELETE p,tr,pm
                    FROM {$wpdb->prefix}posts p
                    LEFT JOIN {$wpdb->prefix}term_relationships tr
                        ON (p.ID = tr.object_id)
                    LEFT JOIN {$wpdb->prefix}postmeta pm
                        ON (p.ID = pm.post_id)
                    WHERE p.post_type = 'vcsw_bookmakers';";

$result = $wpdb->query($query);
/**
 * EOF Delete all Bookmakers CPT posts & postmeta & terms.
 */

/**
 * Delete all custom DB tables.
 */
$custom_db_tables = [
	$wpdb->prefix . 'vcsw_competition',
	$wpdb->prefix . 'vcsw_markets',
	$wpdb->prefix . 'vcsw_sport_types',
];

foreach($custom_db_tables as $custom_db_table) {
	$result = $wpdb->query("DROP TABLE IF EXISTS $custom_db_table");
}
/**
 * EOF Delete all custom DB tables.
 */
