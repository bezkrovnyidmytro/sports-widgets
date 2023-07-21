<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

use DmytroBezkrovnyi\SportsWidgets\Model\MarketModel;

class MarketsSettingsPage extends AbstractSettingsPage
{
	private string $parent_page_slug;

	public function __construct(string $parent_page_slug = '')
	{
		$this->page_title = __('Markets', VCSW_DOMAIN);
		$this->menu_title = __('Markets', VCSW_DOMAIN);
		$this->capability = 'manage_options';
		$this->menu_slug = $parent_page_slug . '_markets';
		$this->template = 'template-markets-settings-page.php';
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
			[
				$this,
				'render'
			]
		);
	}
	public function render()
	{
		$viewArgs = [
			'page_title' => $this->page_title,
			'markets_list' => MarketModel::getAllData(),
			'selected_markets' => MarketModel::getSelected(),
		];
		$this->view->render($this->template, $viewArgs);
	}
}
