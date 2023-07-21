<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

use DmytroBezkrovnyi\SportsWidgets\Model\FontsModel;
use DmytroBezkrovnyi\SportsWidgets\Request\Request;

class StylesSettingsPage extends AbstractSettingsPage
{
    private string $parent_page_slug;
    
    public function __construct(string $parent_page_slug = '')
    {
        $this->page_title       = __('Fonts & Styles customizer', VCSW_DOMAIN);
        $this->menu_title       = __('Styles customizer', VCSW_DOMAIN);
        $this->capability       = 'manage_options';
        $this->menu_slug        = $parent_page_slug . '_styles';
        $this->template         = 'template-styles-settings-page.php';
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
            'page_title'             => $this->page_title,
            'fonts'                  => FontsModel::getAllCdnFonts(),
            'selected_font'          => FontsModel::getCustomFont(),
            'default_competition_id' => Request::getSelectedDataSource() === 'production'
                ? 'id 1 goes here' // Premier League (22/23) ID from API production
                : 'id 2 goes here', // Premier League (22/23) ID from API staging
        ];
        
        $this->view->render($this->template, $viewArgs);
    }
}
