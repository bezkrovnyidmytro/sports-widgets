<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

use DmytroBezkrovnyi\SportsWidgets\Model\CompetitionModel;

class CompetitionsSettingsPage extends AbstractSettingsPage
{
    private string $parent_page_slug;
    
    public function __construct(string $parent_page_slug = '')
    {
        $this->page_title       = __('Competitions', VCSW_DOMAIN);
        $this->menu_title       = __('Competitions', VCSW_DOMAIN);
        $this->capability       = 'manage_options';
        $this->menu_slug        = $parent_page_slug . '_competitions';
        $this->template         = 'template-competitions-settings-page.php';
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
        $selectedCompetitions = CompetitionModel::getSelectedData();
        
        // exclude selected Competitions from all Competitions list
        // and show selected in the top of list
        // underneath show all remain Competitions
        $allCompetitions = CompetitionModel::getAllData();
        
        // show selected first - then all the rest
        $sortedCompetitions = CompetitionModel::getSortedBySelection($allCompetitions, $selectedCompetitions);
        
        $viewArgs = [
            'page_title'                  => $this->page_title,
            'competitions_list'           => $sortedCompetitions,
            'count_all_competitions'      => is_countable($allCompetitions) ? count($allCompetitions) : 0,
            'count_selected_competitions' => is_countable($selectedCompetitions) ? count($selectedCompetitions) : 0,
        ];
        
        $this->view->render($this->template, $viewArgs);
    }
}
