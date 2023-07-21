<?php

namespace DmytroBezkrovnyi\SportsWidgets\Registry;

use DmytroBezkrovnyi\SportsWidgets\PluginLoader;
use DmytroBezkrovnyi\SportsWidgets\Taxonomy\GeoLocationTaxonomy;

class CustomTaxonomyRegistry
{
    protected PluginLoader $loader;

    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;

        $this->initHooks();
    }

    private function initHooks()
    {
        $this->loader->add_action('init', $this, 'initCustomTaxonomies');
        $this->loader->add_action('parent_file', $this, 'menuHighlight');

        $this->loader->add_filter('taxonomy_labels_' . GeoLocationTaxonomy::$slug, $this, 'replaceTaxonomyLabels');
        $this->loader->add_filter('manage_edit-' . GeoLocationTaxonomy::$slug . '_columns', $this, 'removeDescriptionField');
        $this->loader->add_filter(GeoLocationTaxonomy::$slug . '_edit_form', $this, 'hideDescriptionField');
        $this->loader->add_filter(GeoLocationTaxonomy::$slug . '_add_form', $this, 'hideDescriptionField');
    }

	public function initCustomTaxonomies()
	{
		new GeoLocationTaxonomy();
	}

	public function menuHighlight($parent_file)
	{
		global $current_screen;

		if ($current_screen->taxonomy === GeoLocationTaxonomy::$slug) {
            return 'vcsw_settings';
		}

		return $parent_file;
	}

	public function replaceTaxonomyLabels($labels)
	{
		/**
		 * Go through all "Location" labels and replace default "Category" label to the correct one
		 */
		if(! empty($labels)) {
			foreach ($labels as &$label) {
				$label = str_replace(
					['Category', 'category', 'Categories', 'categories'],
					['Location', 'location', 'Locations', 'locations'],
					$label
				);
			}
		}

		return $labels;
	}

	public function removeDescriptionField($columns)
	{
		if (! empty($columns['description'])) {
			unset($columns['description']);
		}

		return $columns;
	}

	public static function hideDescriptionField()
	{
		echo '<style>.term-description-wrap{display:none;}</style>';
	}
}
