<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    sports-widgets
 * @subpackage sports-widgets/includes
 */

namespace DmytroBezkrovnyi\SportsWidgets;

class PluginI18N
{
    public static string $optionName = 'vcsw_front_end_language';
    
    protected PluginLoader $loader;
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        
        $this->initHooks();
    }
    
    private function initHooks()
    {
        $this->loader->add_action('plugins_loaded', $this, 'loadPluginAdminTextDomain');
        $this->loader->add_action('plugins_loaded', $this, 'loadPluginPublicTextDomain');
        $this->loader->add_filter('plugin_locale', $this, 'pluginChangeLocale', 10, 2);
    }
    
    public static function getAllSupportedLanguages() : array
    {
        return [
            'en' => 'English',
            'it' => 'Italian',
            'de' => 'German',
            'ja' => 'Japanese',
            'pt' => 'Portugese',
            'es' => 'Spanish',
        ];
    }
    public static function getAllSupportedLanguagesFormat() : array
    {
        $getAllSupportedLanguages = self::getAllSupportedLanguages();
        $getAllSupportedLanguagesFormat = array();

        foreach ($getAllSupportedLanguages as $langCode => $language) {
            $getAllSupportedLanguagesFormat[]= $langCode . '_' . strtoupper($langCode);
        }

        return $getAllSupportedLanguagesFormat;
    }
    
    public static function getSiteDefaultTimeFormat() : string
    {
        $siteTimeFormat = get_option('time_format', 'H:i');
        
        return (preg_match('(h|H)', $siteTimeFormat) === 1) ? 24 : 12;
    }
    
    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function loadPluginAdminTextDomain()
    {
        $getAllSupportedLanguagesFormat = self::getAllSupportedLanguagesFormat();

        if(! is_admin() || ! in_array(get_locale(), $getAllSupportedLanguagesFormat)) {
            return;
        }

        load_plugin_textdomain(
            VCSW_DOMAIN,
            false,
            VCSW_DIR_PATH . '/languages/admin'
        );
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function loadPluginPublicTextDomain()
    {
        if(is_admin()) {
            return;
        }

        load_plugin_textdomain(
            VCSW_DOMAIN,
            false,
            VCSW_DIR_PATH . '/languages/front'
        );
    }
    
    public function pluginChangeLocale($locale, $domain)
    {
        if ($domain === VCSW_DOMAIN) {
            $locale = self::changeFormatSelectedLang();
        }
        
        return $locale;
    }
    
    public static function changeFormatSelectedLang() : string
    {
        $selectedLang = self::getSelectedLang();
        
        return $selectedLang . '_' . strtoupper($selectedLang);
    }
    
    public static function getSelectedLang() : string
    {
        return get_option(self::$optionName, self::getDefaultLang()) ? : self::getDefaultLang();
    }
    
    public static function getDefaultLang() : string
    {
        return 'en';
    }
}
