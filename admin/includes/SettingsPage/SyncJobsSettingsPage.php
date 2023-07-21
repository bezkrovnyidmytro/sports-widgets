<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

class SyncJobsSettingsPage extends AbstractSettingsPage
{
	private string $parent_page_slug;

	public function __construct(string $parent_page_slug = '')
	{
		$this->page_title = __('Sync Jobs', VCSW_DOMAIN);
		$this->menu_title = __('Sync Jobs', VCSW_DOMAIN);
		$this->capability = 'manage_options';
		$this->menu_slug = $parent_page_slug . '_sync_jobs';
		$this->template = 'template-sync-jobs-settings-page.php';
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
        ];

        $this->view->render($this->template, $viewArgs);
    }
}
