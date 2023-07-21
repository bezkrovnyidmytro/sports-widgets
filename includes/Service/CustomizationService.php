<?php

namespace DmytroBezkrovnyi\SportsWidgets\Service;

use DmytroBezkrovnyi\SportsWidgets\Customization\FontsCustomizer;
use DmytroBezkrovnyi\SportsWidgets\Customization\StylesCustomizer;
use DmytroBezkrovnyi\SportsWidgets\PluginLoader;

class CustomizationService
{
    private const HOOKS_PRIORITY = 9999;

    private PluginLoader $loader;

    private FontsCustomizer $fontsCustomizer;

    private StylesCustomizer $stylesCustomizer;

    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;

        $this->fontsCustomizer  = new FontsCustomizer();
        $this->stylesCustomizer = new StylesCustomizer();

        $this->initHooks();
    }

    private function initHooks() : void
    {
        $this->loader->add_action('wp_head', $this, 'enqPublicAssetsCustomization', self::HOOKS_PRIORITY);
        $this->loader->add_action('admin_head', $this, 'enqAdminAssetsCustomization', self::HOOKS_PRIORITY);

        $this->loader->add_action('wp_enqueue_scripts', $this, 'enqPublicCustomFont', self::HOOKS_PRIORITY);
        $this->loader->add_action('admin_enqueue_scripts', $this, 'enqAdminCustomFont', self::HOOKS_PRIORITY);
    }

    public function enqAdminAssetsCustomization() : void
    {
        $this->outputAssetsCustomization();
    }

    private function outputAssetsCustomization() : void
    {
        $customStyles = [];

        if ($this->fontsCustomizer->getCustomFont()) {
            $customStyles [] = sprintf('--vcsw-font-family:%s', $this->fontsCustomizer->getCustomFont());
        }

        if ($this->stylesCustomizer->getCustomStyles()) {
            $customStyles [] = sprintf('%s', $this->stylesCustomizer->getCustomStyles());
        }

        if ($customStyles) {
            echo sprintf('<style id="vcsw--customization-service">body{%s}</style>',
                implode(';', $customStyles)
            );
        }
    }

    public function enqPublicAssetsCustomization() : void
    {
        $this->outputAssetsCustomization();
    }

    public function enqPublicCustomFont() : void
    {
        $this->fontsCustomizer->enqueueCustomFont();
    }

    public function enqAdminCustomFont(string $hook = '') : void
    {
        if (
            in_array($hook, ['post-new.php', 'post.php']) // edit pages with Gutenberg editor
            || strpos($hook, 'vcsw_settings_shortcode_generator') !== false // plugin page with shortcode generator
            || strpos($hook, 'vcsw_settings_styles') !== false // plugin page with styles settings
        ) {
            $this->fontsCustomizer->enqueueCustomFont();
        }
    }
}
