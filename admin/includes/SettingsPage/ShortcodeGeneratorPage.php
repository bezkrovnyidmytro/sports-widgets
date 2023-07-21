<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

use DmytroBezkrovnyi\SportsWidgets\Model\CompetitionModel;
use DmytroBezkrovnyi\SportsWidgets\Model\SeasonsModel;
use DmytroBezkrovnyi\SportsWidgets\Model\SportTypeModel;
use DmytroBezkrovnyi\SportsWidgets\Shortcode\LeagueStandingsShortcode;
use DmytroBezkrovnyi\SportsWidgets\Shortcode\SportsFixturesShortcode;

class ShortcodeGeneratorPage extends AbstractSettingsPage
{
	private string $parent_page_slug;

	public function __construct(string $parent_page_slug = '')
	{
		$this->page_title = __('Shortcode generator', VCSW_DOMAIN);
		$this->menu_title = __('Shortcode generator', VCSW_DOMAIN);
		$this->capability = 'manage_options';
		$this->menu_slug = $parent_page_slug . '_shortcode_generator';
		$this->template = 'template-shortcode-generator-settings-page.php';
		$this->parent_page_slug = $parent_page_slug;

		parent::__construct();
	}

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
			'competitions' => CompetitionModel::getSelectedData(),
			'sports_types' => SportTypeModel::getSelected(),
			'seasons' => SeasonsModel::getAllSeasons(),
			'shortcodes_list' => [
				SportsFixturesShortcode::getShortcodeName(),
				LeagueStandingsShortcode::getShortcodeName(),
			],
		];
		$this->view->render($this->template, $viewArgs);
	}
}
