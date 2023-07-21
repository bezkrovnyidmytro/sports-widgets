<?php

namespace DmytroBezkrovnyi\SportsWidgets\Taxonomy;

use DmytroBezkrovnyi\SportsWidgets\CPT\BookmakerCPT;
use DmytroBezkrovnyi\SportsWidgets\Entity\AbstractTaxonomy;

class GeoLocationTaxonomy extends AbstractTaxonomy
{
	public string $singularName = 'Location';
	public string $pluralName = 'Locations';

	public string $name = 'locations';

	public static string $slug = 'vcsw_geo_location';
	public string $restBaseSlug = 'vcsw_geo_location';

	public static string $optionName = 'vcsw_add_countries';

	public function __construct()
	{
		$this->registerTaxonomy();
		$this->addCountries();
	}

	protected function registerTaxonomy()
	{
		if(! function_exists('register_taxonomy')) {
			return;
		}
		/**
		 * Taxonomy: Locations.
		 */
		register_taxonomy( self::$slug, [BookmakerCPT::$postType], $this->getArgs());
    }

	protected function getArgs(): array
    {
		/**
		 * Taxonomy args: Locations.
		 */
		$labels = [
			'name'          => __( $this->pluralName, VCSW_DOMAIN),
			'singular_name' => __( $this->singularName, VCSW_DOMAIN),
			'menu_name'     => __( $this->pluralName, VCSW_DOMAIN),
			'all_items'     => __( 'All ' . $this->singularName, VCSW_DOMAIN),
			'edit_item'     => __( 'Edit ' . $this->singularName, VCSW_DOMAIN),
			'view_item'     => __( 'View ' . $this->singularName, VCSW_DOMAIN),
			'name_field_description'     => __( 'Please enter a country name.', VCSW_DOMAIN),
			'slug_field_description'     => __( 'Please enter the country code according to the following standard: <a href="https://www.nationsonline.org/oneworld/country_code_list.htm" target="_blank" rel="noindex nofollow noopener">ISO 3166 Alpha-2 code</a>.', VCSW_DOMAIN),
			'parent_field_description'     => __( 'If you want to add a new state, select the country to which this state belongs (relevant for Australia and the USA).', VCSW_DOMAIN),
		];

		return [
			'label'              => __( $this->pluralName, VCSW_DOMAIN),
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'hierarchical'       => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'show_in_nav_menus'  => false,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => self::$slug, 'with_front' => true,],
			'show_admin_column'  => true,
			'show_in_rest'       => true,
			'rest_base'          => $this->restBaseSlug,
			'rest_controller_class' => 'WP_REST_Terms_Controller',
			'show_in_quick_edit'    => false,
		];
	}

	private function addCountries()
	{
		if(! function_exists('get_option')) {
			return;
		}

		if (get_option(self::$optionName) != 'completed') {
			$country_array = [
				'au' => 'Australia',
				'cm' => 'Cameroon',
				'ca' => 'Canada',
				'fr' => 'France',
				'de' => 'Germany',
				'gh' => 'Ghana',
				'in' => 'India',
				'ie' => 'Ireland',
				'it' => 'Italy',
				'ke' => 'Kenya',
				'mu' => 'Mauritius',
				'ma' => 'Morocco',
				'mm' => 'Myanmar',
				'nl' => 'Netherlands',
				'ng' => 'Nigeria',
				'pt' => 'Portugal',
				'sg' => 'Singapore',
				'za' => 'South Africa',
				'tz' => 'Tanzania, United Republic of',
				'tn' => 'Tunisia',
				'tr' => 'Turkey',
				'ug' => 'Uganda',
				'gb' => 'United Kingdom',
				'us' => 'United States of America',
				'zm' => 'Zambia',
				'zw' => 'Zimbabwe',
				'ua' => 'Ukraine',
			];

			// Loop through array and insert terms
			foreach ($country_array as $abbr => $name) {
				$countryName = ucwords(strtolower($name));

				if (! get_term_by('name', $countryName, self::$slug)) {
					wp_insert_term($countryName, self::$slug, ['slug' => $abbr]);
				}
			}

			$states_array = [
				'au' => [
					'au-nsw' => 'New South Wales',
					'au-vic' => 'Victoria',
					'au-qld' => 'Queensland',
					'au-wa' => 'Western Australia',
					'au-sa' => 'South Australia',
					'au-tas' => 'Tasmania',
				],
				'us' => [
					// ...
				]
			];

			foreach ($states_array as $abbr => $states) {
				$parentTerm = get_term_by('slug', $abbr, self::$slug);
				foreach($states as $state_abbr => $state_name) {

					if (! get_term_by('slug', $state_name, self::$slug)) {
						wp_insert_term($state_name, self::$slug, ['slug' => $state_abbr, 'parent' => $parentTerm->term_id]);
					}
				}
			}

			update_option(self::$optionName, 'completed');
		}
	}
}
