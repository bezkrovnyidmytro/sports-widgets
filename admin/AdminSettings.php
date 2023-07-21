<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin;

use DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage\BookmakersSettingsPage;
use DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage\CompetitionsSettingsPage;
use DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage\GeneralSettingsPage;
use DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage\HelpSettingsPage;
use DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage\LocationsPage;
use DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage\MarketsSettingsPage;
use DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage\StylesSettingsPage;
use DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage\PopupSettingsPage;
use DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage\ShortcodeGeneratorPage;
use DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage\SyncJobsSettingsPage;
use DmytroBezkrovnyi\SportsWidgets\PluginLoader;

class AdminSettings
{
    protected PluginLoader $loader;

    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;

        $this->initHooks();
    }

    private function initHooks()
    {
        $this->loader->add_action('admin_menu', $this, 'initAdminPages');
    }

	/**
	 * @return void
	 */
	public function initAdminPages()
	{
		$parentPage = new GeneralSettingsPage();

		$parentPageSlug = $parentPage->getMenuSlug();

		new BookmakersSettingsPage($parentPageSlug);
		new LocationsPage($parentPageSlug);
		new CompetitionsSettingsPage($parentPageSlug);
		new MarketsSettingsPage($parentPageSlug);
        new PopupSettingsPage($parentPageSlug);
		new ShortcodeGeneratorPage($parentPageSlug);
        new SyncJobsSettingsPage($parentPageSlug);
		new StylesSettingsPage($parentPageSlug);
        new HelpSettingsPage($parentPageSlug);
    }
}
