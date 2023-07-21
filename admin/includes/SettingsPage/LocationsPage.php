<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

use DmytroBezkrovnyi\SportsWidgets\Taxonomy\GeoLocationTaxonomy;

class LocationsPage extends AbstractSettingsPage
{
	private string $parent_page_slug;

	public function __construct(string $parent_page_slug = '')
	{
		$this->page_title = __('Locations', VCSW_DOMAIN);
		$this->menu_title = __('Locations', VCSW_DOMAIN);
		$this->capability = 'manage_options';
		$this->menu_slug = '/edit-tags.php?taxonomy='.GeoLocationTaxonomy::$slug;
		$this->parent_page_slug = $parent_page_slug;

		parent::__construct();
	}

	/**
	 * @return void
	 */
	public function addMenuPage()
	{
		add_submenu_page(
			$this->parent_page_slug,
			$this->page_title,
			$this->menu_title,
			$this->capability,
			$this->menu_slug,
		);
	}
}
