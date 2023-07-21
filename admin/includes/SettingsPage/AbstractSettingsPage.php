<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

use DmytroBezkrovnyi\SportsWidgets\Admin\ViewAdminSettings;

abstract class AbstractSettingsPage
{
	protected string $page_title;
	protected string $menu_title;
	protected string $menu_slug;
	protected string $capability;
	protected string $template;

	protected ?ViewAdminSettings $view;

	public function __construct()
	{
		$this->view = new ViewAdminSettings();

		$this->addMenuPage();
	}

	/**
	 * @return mixed
	 */
	abstract public function addMenuPage();

	/**
	 * @return void
	 */
	public function render()
	{
		$this->view->render($this->template, ['page_title' => $this->page_title]);
	}

	/**
	 * @return string
	 */
	public function getMenuSlug(): string
	{
		return $this->menu_slug;
	}
}
