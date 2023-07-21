<?php

namespace DmytroBezkrovnyi\SportsWidgets\Shortcode;

use DmytroBezkrovnyi\SportsWidgets\Entity\AbstractShortcode;

class SportsFixturesShortcode extends AbstractShortcode
{
    protected static string $shortcodeName = 'sports_fixtures';
    
    protected static array $defaultArgs = [
        'sports_type' => 'football',
        'competition' => '',
    ];
    
    private static int $shortcodesCounter = 1;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function render($attrs) : string
    {
        $blockAttrsJson = self::getBlockAttrs($attrs);
        
        if (empty($blockAttrsJson)) {
            return '';
        }
        
        return self::getHtmlResponse(self::getShortcodeName() . '-' . self::$shortcodesCounter++, $blockAttrsJson);
    }
}
