<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes\SettingsPage;

class HelpSettingsPage extends AbstractSettingsPage
{
    private string $parent_page_slug;
    
    public function __construct(string $parent_page_slug = '')
    {
        $this->page_title       = __('Help', VCSW_DOMAIN);
        $this->menu_title       = __('Help', VCSW_DOMAIN);
        $this->capability       = 'manage_options';
        $this->menu_slug        = $parent_page_slug . '_help';
        $this->template         = 'template-help-settings-page.php';
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
            'page_title'   => $this->page_title,
            'page_content' => self::getPageContent()
        ];
        $this->view->render($this->template, $viewArgs);
    }
    
    private static function getPageContent() : string
    {
        $pageContent = '';
        
        if (file_exists(VCSW_DIR_PATH . 'README.md')
            && class_exists('Parsedown')
            && method_exists('Parsedown', 'text')) {
            
            $pageContent = file_get_contents(VCSW_DIR_PATH . 'README.md');
            
            $parsedown     = new \Parsedown();
            $parsedContent = $parsedown->text($pageContent);
            
            if (! empty($parsedContent)) {
                $pageContent = str_replace('guide/', VCSW_URL_PATH . 'guide/', $parsedContent);
            }
        }
        
        return $pageContent;
    }
}
