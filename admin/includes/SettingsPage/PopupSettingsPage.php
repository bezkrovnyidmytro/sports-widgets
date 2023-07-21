<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

use DmytroBezkrovnyi\SportsWidgets\Entity\Popup;
use DmytroBezkrovnyi\SportsWidgets\Model\MarketModel;

class PopupSettingsPage extends AbstractSettingsPage
{
    private string $parent_page_slug;
    
    public function __construct(string $parent_page_slug = '')
    {
        $this->page_title       = __('Odds popup', VCSW_DOMAIN);
        $this->menu_title       = __('Odds popup', VCSW_DOMAIN);
        $this->capability       = 'manage_options';
        $this->menu_slug        = $parent_page_slug . '_odds_popup';
        $this->template         = 'template-odds-popup-settings-page.php';
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
            'page_title'       => $this->page_title,
            'popup_settings'   => Popup::getSettings(),
            'selected_markets' => MarketModel::getSelectedData(),
        ];
        
        $this->view->render($this->template, $viewArgs);
    }
}
