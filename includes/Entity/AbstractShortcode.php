<?php

namespace DmytroBezkrovnyi\SportsWidgets\Entity;

abstract class AbstractShortcode
{
    protected static string $shortcodeName;
    
    protected static array $defaultArgs;
    
    public function __construct()
    {
        if (shortcode_exists(static::$shortcodeName)) {
            return;
        }
        
        add_shortcode(static::$shortcodeName, [$this, 'render']);
    }
    
    /**
     * @return array
     */
    public static function getDefaultArgs() : array
    {
        return static::$defaultArgs;
    }
    
    public static function getShortcodeName() : string
    {
        return static::$shortcodeName;
    }
    
    protected static function getBlockAttrs($attrs)
    {
        $preparedAttrs = static::prepareAttrs($attrs);
        
        if (empty($preparedAttrs)) {
            return '';
        }
        
        return static::getAttrsJson($preparedAttrs);
    }
    
    protected static function prepareAttrs($attrs) : array
    {
        return shortcode_atts(static::$defaultArgs, $attrs);
    }
    
    protected static function getAttrsJson($attrs)
    {
        if (empty($attrs)) {
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
            '<div class="vcsw-sports-widgets shortcode %s" id="%s"><script type="application/json">%s</script></div>',
            static::getShortcodeInternalHandleName(),
            $blockId,
            $blockAttrsJson
        );
    }
    
    protected static function getShortcodeInternalHandleName() : string
    {
        return 'vcsw-' . str_replace(['-block', '-shortcode', '_'], ['', '', '-'], static::$shortcodeName);
    }
    
    abstract public function render($attrs);
}
