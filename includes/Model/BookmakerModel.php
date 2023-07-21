<?php

namespace DmytroBezkrovnyi\SportsWidgets\Model;

use DmytroBezkrovnyi\SportsWidgets\CPT\BookmakerCPT;
use DmytroBezkrovnyi\SportsWidgets\Taxonomy\GeoLocationTaxonomy;
use Exception;

/**
 * BookmakerModel class
 */
class BookmakerModel extends AbstractModel
{
    public static string $optionName = 'vcsw_selected_bookmakers';
    
    public static function truncateDatabaseTable() : bool
    {
        $posts = get_posts(
            [
                'post_type'   => BookmakerCPT::$postType,
                'numberposts' => -1
            ]
        );
        
        if (! empty($posts)) {
            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }
        
        return true;
    }
    
    public static function getAllData(array $selectedBookmakers = []) : array
    {
        $args = [
            'post_type'      => BookmakerCPT::$postType,
            'posts_per_page' => -1,
        ];
        
        $posts = get_posts($args);
        
        if (! empty($selectedBookmakers)) {
            $posts = self::getSortedBySelection($posts, $selectedBookmakers);
        }
        
        return $posts;
    }
    
    public static function getSortedBySelection(array $bookmakers = [], array $sort_order = []) : array
    {
        usort($bookmakers, function ($a, $b) use ($sort_order) {
            $a_meta = get_post_meta($a->ID, 'bookmaker_id', true);
            $b_meta = get_post_meta($b->ID, 'bookmaker_id', true);
            
            $a = array_search($a_meta, $sort_order);
            $b = array_search($b_meta, $sort_order);
            
            if ($a === false && $b === false) { // both items are don't care
                return 0;                       // a == b (or add tie-breaker condition)
            }
            elseif ($a === false) {           // $a is a dont care
                return 1;                       // $a > $b
            }
            elseif ($b === false) {           // $b is a dont care
                return -1;                      // $a < $b
            }
            else {
                return $a - $b;                 // sort $a and $b ascending
            }
        });
        
        return $bookmakers;
    }
    
    public static function getSelected()
    {
        return get_option(self::$optionName) ? : [];
    }
    
    public static function saveDataFromCore(array $bookmakers = [])
    {
        if (empty($bookmakers)) {
            return;
        }
        
        foreach ($bookmakers as $bookmaker) {
            if (empty($bookmaker)) {
                continue;
            }
            
            $data = [
                'post_title'  => $bookmaker['name'],
                'post_name'   => $bookmaker['bookmaker'],
                'post_status' => 'publish',
                'post_type'   => BookmakerCPT::$postType,
                'post_author' => 1,
            ];
            
            $postId = self::getPostByTitle($bookmaker['name']);
            
            if (! empty($postId)) {
                $data['ID'] = $postId;
                $postId     = wp_update_post(wp_slash($data), true);
            }
            else {
                $postId = wp_insert_post(wp_slash($data), true);
            }
            
            update_post_meta($postId, 'bookmaker_id', $bookmaker['_id']);
        }
    }
    
    private static function getPostByTitle(string $title = '')
    {
        if (empty($title)) {
            return null;
        }
        
        $_args = [
            'post_type'              => BookmakerCPT::$postType,
            'title'                  => $title,
            'numberposts'            => 1,
            'fields'                 => 'ids',
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
        ];
        
        $posts = get_posts($_args);
        
        return ! empty($posts) ? $posts[0] : null;
    }
    
    public static function getGeoLocatedBookmakersIds(
        array $selected_bookmakers = [],
        string $user_location = ''
    ) : array
    {
        $query_args = [
            'post_type'      => BookmakerCPT::$postType,
            'fields'         => 'ids',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'tax_query'      => [
                [
                    'taxonomy' => GeoLocationTaxonomy::$slug,
                    'operator' => 'NOT EXISTS',
                ],
            ],
        ];
        
        if (! empty($user_location)) {
            $query_args['tax_query'][] = [
                'taxonomy'         => GeoLocationTaxonomy::$slug,
                'field'            => 'slug',
                'terms'            => [$user_location],
                'include_children' => true,
            ];
            
            $query_args['tax_query']['relation'] = 'OR';
        }
        
        if (empty($selected_bookmakers)) {
            // if there are no selected bookmakers in admin table
            // return list of all bookmakers, BUT with geolocated flag
            $query_args['posts_per_page'] = -1;
            
            return get_posts($query_args);
        }
        
        $result_posts = [];
        
        // ORDER POSTS BY SELECTED BOOKMAKERS STORED IN WP OPTIONS TABLE
        foreach ($selected_bookmakers as $core_bookmaker_id) {
            $query_args['meta_query'] = [
                [
                    'key'     => 'bookmaker_id',
                    'compare' => '=',
                    'value'   => $core_bookmaker_id,
                ]
            ];
            
            $_posts = get_posts($query_args);
            
            if (! empty($_posts[0])) {
                $result_posts[] = $_posts[0];
            }
        }
        
        return array_unique(array_filter($result_posts));
    }
    
    /**
     * @throws Exception
     */
    public static function getSelectedData() : array
    {
        global $vcsw_plugin;
        $userLocation    = $vcsw_plugin->getUserService()->getUserLocation();
        $userCountryCode = ! empty($userLocation['country_code']) ? $userLocation['country_code'] : '';
        
        $selectedBookmakers = self::getSelected();
        
        if (empty($selectedBookmakers)) {
            return [];
        }
        
        $bookmakersPosts = self::getGeoLocatedBookmakersIds($selectedBookmakers, $userCountryCode);
        
        if (empty($bookmakersPosts)) {
            return [];
        }
        
        $allBookmakersData = [];
        
        foreach ($bookmakersPosts as $bookmakersPostId) {
            $bookmaker_core_id = get_post_meta($bookmakersPostId, 'bookmaker_id', true) ? : '';
            $logo_bg_color     = get_post_meta($bookmakersPostId, 'bg_color', true) ? : '';
            $affiliate_link    = get_post_meta($bookmakersPostId, 'affiliate_link', true) ? : '';
            
            $allBookmakersData[] = [
                // WP Properties
                'logo'           => has_post_thumbnail($bookmakersPostId) ? get_the_post_thumbnail_url($bookmakersPostId
                ) : '',
                'title'          => get_the_title($bookmakersPostId),
                'slug'           => get_post_field('post_name', $bookmakersPostId),
                // EOF WP Properties
                // Custom Properties
                '_id'            => $bookmaker_core_id, // ID from CORE
                'bg_color'       => $logo_bg_color, // background color for logo
                'affiliate_link' => $affiliate_link, // external link with tracking parameters
                // EOF Custom Properties
            ];
        }
        
        return $allBookmakersData;
    }
    
    public static function createDatabaseTable() : bool
    {
        // We do not need to create custom database table for bookmakers,
        // because all posts data stored into wp_posts, as "Bookmakers" is a Custom Post Type.
        return true;
    }
    
    public static function deleteAllPosts()
    {
        // Use this method as a fallback option to remove whole Bookmaker CPT posts from database;
        global $wpdb;
        
        $postType = BookmakerCPT::$postType;
        
        $query = "DELETE p,tr,pm
                    FROM {$wpdb->prefix}posts p
                    LEFT JOIN {$wpdb->prefix}term_relationships tr
                        ON (p.ID = tr.object_id)
                    LEFT JOIN {$wpdb->prefix}postmeta pm
                        ON (p.ID = pm.post_id)
                    WHERE p.post_type = '$postType';";
        
        return $wpdb->query($query);
    }
}
