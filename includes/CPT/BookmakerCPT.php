<?php

namespace DmytroBezkrovnyi\SportsWidgets\CPT;

use DmytroBezkrovnyi\SportsWidgets\Taxonomy\GeoLocationTaxonomy;

class BookmakerCPT
{
    public static string $postType = 'vcsw_bookmakers';
    
    public string $name = 'Bookmakers';
    
    public string $singularName = 'Bookmaker';
    
    public string $pluralName = 'Bookmakers';
    
    public function __construct()
    {
        $this->registerPostType();
    }
    
    public function registerPostType()
    {
        if (! function_exists('register_post_type')) {
            return;
        }
        
        if (post_type_exists(self::$postType)) {
            unregister_post_type(self::$postType);
        }
        
        /**
         * CPT: Sports Widget Bookmakers.
         */
        register_post_type(self::$postType, $this->getArgs());
    }
    
    protected function getArgs() : array
    {
        /**
         * CPT args: Bookmaker.
         */
        $labels = [
            'name'                  => __($this->name, VCSW_DOMAIN),
            'singular_name'         => __($this->singularName, VCSW_DOMAIN),
            'add_new'               => __('Add new ' . $this->singularName, VCSW_DOMAIN),
            'add_new_item'          => __('Add new ' . $this->singularName, VCSW_DOMAIN),
            'edit_item'             => __('Edit ' . $this->singularName, VCSW_DOMAIN),
            'new_item'              => __('New ' . $this->singularName, VCSW_DOMAIN),
            'view_item'             => __('View ' . $this->singularName, VCSW_DOMAIN),
            'search_items'          => __('Search ' . $this->singularName, VCSW_DOMAIN),
            'not_found'             => __('Not ' . $this->pluralName . ' found', VCSW_DOMAIN),
            'not_found_in_trash'    => __('Not ' . $this->pluralName . ' found in trash', VCSW_DOMAIN),
            'menu_name'             => __($this->name, VCSW_DOMAIN),
            'featured_image'        => __('Bookmaker logo', VCSW_DOMAIN),
            'set_featured_image'    => __('Set bookmaker logo', VCSW_DOMAIN),
            'remove_featured_image' => __('Remove bookmaker logo', VCSW_DOMAIN),
            'use_featured_image'    => __('Use bookmaker logo', VCSW_DOMAIN),
        ];
        
        return [
            'labels'              => $labels,
            'description'         => __('Custom post type Bookmakers for Sports Widget plugin', VCSW_DOMAIN),
            'public'              => true,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'show_ui'             => null,
            'show_in_nav_menus'   => false,
            'show_in_menu'        => false,
            'show_in_rest'        => true,
            'rest_base'           => self::$postType,
            'menu_icon'           => 'dashicons-star-filled',
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => ['title', 'author', 'thumbnail', 'post-thumbnails'],
            'taxonomies'          => [GeoLocationTaxonomy::$slug],
            'query_var'           => true,
        ];
    }
    
    public static function setBookmakerLabels($labels)
    {
        /**
         * Go through all "Custom Post Type" labels and replace default "Post" label to the correct one
         */
        if (! empty($labels)) {
            foreach ($labels as $key => &$label) {
                $label = str_replace(
                    ['Post', 'post', 'Posts', 'posts'],
                    ['Bookmaker', 'bookmaker', 'Bookmakers', 'bookmakers'],
                    $label
                );
            }
        }
        
        return $labels;
    }
}
