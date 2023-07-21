<?php

namespace DmytroBezkrovnyi\SportsWidgets\Entity;

use DmytroBezkrovnyi\SportsWidgets\Registry\BlockRegistry;

abstract class AbstractBlock
{
    protected static string $blockName;
    
    protected static string $blockTitle;
    
    protected static array $defaultArgs;
    
    public function __construct()
    {
        static::registerBlock();
    }
    
    protected function registerBlock()
    {
        $jsonRegistryFile = static::getBlockRegistryJsonFile();
        
        if (! file_exists($jsonRegistryFile)) {
            return false;
        }
        
        return register_block_type(
            $jsonRegistryFile,
            [
                'render_callback' => [$this, 'render'],
                'title'           => static::$blockTitle,
            ],
        );
    }
    
    protected function getBlockRegistryJsonFile() : string
    {
        return sprintf('%s/build/blocks/%s/block.json', VCSW_DIR_PATH, static::$blockName);
    }
    
    /**
     * @return string
     */
    public static function getBlockName() : string
    {
        return self::$blockName;
    }
    
    abstract public static function render(array $attrs);
    
    /**
     * @return string
     */
    public static function getFullBlockName() : string
    {
        return BlockRegistry::$blockNamePrefix . static::$blockName;
    }
    
    protected static function getBlockAttrs(array $attrs = [])
    {
        $preparedAttrs = static::prepareAttrs($attrs);
        
        if (empty($preparedAttrs)) {
            return '';
        }
        
        return static::getAttrsJson($preparedAttrs);
    }
    
    protected static function prepareAttrs(array $attrs = []) : array
    {
        $ret = [];
        
        foreach (static::$defaultArgs as $name => $default) {
            $ret[$name] = array_key_exists($name, $attrs) && ! empty($attrs[$name])
                ? $attrs[$name]
                : $default;
        }
        
        return $ret;
    }
    
    protected static function getAttrsJson(array $attrs = [])
    {
        if (! $attrs) {
            return '';
        }
        
        $attrsJson = json_encode($attrs);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return '';
        }
        
        return $attrsJson;
    }
    
    protected static function getHtmlResponse(
        string $blockId = '',
        string $blockAttrsJson = ''
    ) : string
    {
        return sprintf(
            '<div class="vcsw-sports-widgets block %s" id="%s"><script type="application/json">%s</script></div>',
            static::getBlockInternalHandleName(),
            $blockId,
            $blockAttrsJson
        );
    }
    
    public static function getBlockInternalHandleName() : string
    {
        return 'vcsw-' . str_replace(['-block', '-shortcode', '_'], ['', '', '-'], static::$blockName);
    }
}
