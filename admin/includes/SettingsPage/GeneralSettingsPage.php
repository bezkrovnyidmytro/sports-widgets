<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

use DmytroBezkrovnyi\SportsWidgets\Model\SportTypeModel;
use DmytroBezkrovnyi\SportsWidgets\PluginI18N;
use DmytroBezkrovnyi\SportsWidgets\Request\Request;

class GeneralSettingsPage extends AbstractSettingsPage
{
    public function __construct()
    {
        $this->page_title = __('Sports Widgets settings', VCSW_DOMAIN);
        $this->menu_title = __('Sports Widgets', VCSW_DOMAIN);
        $this->capability = 'manage_options';
        $this->menu_slug  = 'vcsw_settings';
        $this->template   = 'template-general-settings-page.php';

        parent::__construct();
    }

    /**
     * @return void
     */
    public function addMenuPage()
    {
        add_menu_page(
            $this->page_title,
            $this->menu_title,
            $this->capability,
            $this->menu_slug,
            [
                $this,
                'render'
            ],
            VCSW_URL_PATH . 'admin/assets/images/sw-icon.svg'
        );
    }

    public function render()
    {
        $viewArgs = [
            'page_title'                 => $this->page_title,
            'vcsw_core_data_source'      => Request::getSelectedDataSource(),
            'core_data_sources'          => Request::getDataSources(),
            'vcsw_front_end_language'    => PluginI18N::getSelectedLang(),
            'languages'                  => PluginI18N::getAllSupportedLanguages(),
            'vcsw_selected_sport_types'  => SportTypeModel::getSelected(),
            'core_supported_sport_types' => SportTypeModel::getAllData(),
        ];

        $this->view->render($this->template, $viewArgs);
    }
}
