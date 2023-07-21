<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package    sports-widgets
 * @subpackage sports-widgets/includes
 */

namespace DmytroBezkrovnyi\SportsWidgets;

use DmytroBezkrovnyi\SportsWidgets\Addons\DfiAddon;
use DmytroBezkrovnyi\SportsWidgets\Addons\RestCacheAddon;
use DmytroBezkrovnyi\SportsWidgets\Addons\WpRocketAddon;
use DmytroBezkrovnyi\SportsWidgets\Admin\AdminSettings;
use DmytroBezkrovnyi\SportsWidgets\Admin\includes\AjaxHandlers;
use DmytroBezkrovnyi\SportsWidgets\Cron\Cron;
use DmytroBezkrovnyi\SportsWidgets\Entity\User;
use DmytroBezkrovnyi\SportsWidgets\Registry\BlockRegistry;
use DmytroBezkrovnyi\SportsWidgets\Registry\CustomPostTypeRegistry;
use DmytroBezkrovnyi\SportsWidgets\Registry\CustomTaxonomyRegistry;
use DmytroBezkrovnyi\SportsWidgets\Registry\MetaboxRegistry;
use DmytroBezkrovnyi\SportsWidgets\Registry\ShortcodesRegistry;
use DmytroBezkrovnyi\SportsWidgets\Request\Request;
use DmytroBezkrovnyi\SportsWidgets\Service\BlockService;
use DmytroBezkrovnyi\SportsWidgets\Service\UserService;
use Exception;

class PluginMain
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      PluginLoader $loader Maintains and registers all hooks for the plugin.
     */
    protected PluginLoader $loader;
    
    private User $user;
    
    private UserService $userService;
    
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @throws Exception
     * @since    1.0.0
     */
    public function __construct()
    {
        $this->loadDependencies();
        $this->setLocale();
        $this->initRequest();
        $this->pluginAssets();
        $this->initTaxonomies();
        $this->setUser();
        $this->setUserService();
        $this->initCustomPostTypes();
        $this->initMetaBoxes();
        $this->initAdmin();
        $this->initAdminAjax();
        $this->createCronTasks();
        $this->blockRegistry();
        $this->shortcodeRegistry();
        $this->pluginAddons();
        $this->miscellaneousHooks();
    }
    
    private function loadDependencies()
    {
        $this->loader = new PluginLoader();
    }
    
    private function setLocale()
    {
        new PluginI18N($this->loader);
    }
    
    private function initRequest()
    {
        (new Request());
    }
    
    private function pluginAssets()
    {
        new PluginAssets($this->loader);
    }
    
    private function initTaxonomies()
    {
        new CustomTaxonomyRegistry($this->loader);
    }
    
    private function setUser()
    {
        $this->user = new User();
    }
    
    private function initCustomPostTypes()
    {
        new CustomPostTypeRegistry($this->loader);
    }
    
    private function initMetaBoxes()
    {
        new MetaboxRegistry($this->loader);
    }
    
    private function initAdmin()
    {
        new AdminSettings($this->loader);
    }
    
    private function initAdminAjax()
    {
        new AjaxHandlers($this->loader);
    }
    
    private function createCronTasks()
    {
        new Cron($this->loader);
    }
    
    private function blockRegistry()
    {
        new BlockRegistry($this->loader);
        new BlockService($this->loader);
    }
    
    private function shortcodeRegistry()
    {
        new ShortcodesRegistry($this->loader);
    }
    
    private function pluginAddons()
    {
        new RestCacheAddon($this->loader);
        new WpRocketAddon($this->loader);
        new DfiAddon($this->loader);
    }
    
    private function miscellaneousHooks()
    {
        $helpers = new Helpers();
        $this->loader->add_action('after_setup_theme', $helpers, 'addFeaturedImageSupport');
        $this->loader->add_filter('upload_mimes', $helpers, 'allowUploadSvgCallback');
        
        if (! is_admin()) {
            $this->loader->add_filter('body_class', $this->userService, 'addUserLocationToBodyClass');
        }
    }
    
    public function run()
    {
        $this->loader->run();
    }
    
    public function getLoader() : PluginLoader
    {
        return $this->loader;
    }
    
    /**
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }
    
    /**
     * @return UserService
     */
    public function getUserService() : UserService
    {
        return $this->userService;
    }
    
    /**
     * @throws Exception
     */
    public function setUserService() : void
    {
        $this->userService = new UserService($this->user);
    }
}
