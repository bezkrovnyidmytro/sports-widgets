<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

use DmytroBezkrovnyi\SportsWidgets\Model\BookmakerModel;

class BookmakersSettingsPage extends AbstractSettingsPage
{
	private string $parent_page_slug;

	public function __construct(string $parent_page_slug = '')
	{
		$this->page_title = __('Bookmakers', VCSW_DOMAIN);
		$this->menu_title = __('Bookmakers', VCSW_DOMAIN);
		$this->capability = 'manage_options';
		$this->menu_slug = $parent_page_slug . '_bookmakers';
		$this->template = 'template-bookmakers-settings-page.php';
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
		$selectedBookmakers = BookmakerModel::getSelected();
		// exclude selected bookmakers from all bookmakers list
	    // and show selected in the top of list
	    // underneath show all remain bookmakers
		$allBookmakers = BookmakerModel::getAllData($selectedBookmakers);

        $viewArgs = [
            'page_title' => $this->page_title,
	        'bookmakers' => $allBookmakers,
	        'selected_bookmakers' => $selectedBookmakers,
        ];

        $this->view->render($this->template, $viewArgs);
    }
}
