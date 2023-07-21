<?php

namespace DmytroBezkrovnyi\SportsWidgets\Shortcode;

use DmytroBezkrovnyi\SportsWidgets\Entity\AbstractShortcode;

class LeagueStandingsShortcode extends AbstractShortcode
{
    protected static string $shortcodeName = 'league_standings';
    
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
        // if competition is empty - nothing to show and return from func
        if (empty($attrs['competition'])) {
            return '';
        }
        
        $blockAttrsJson = self::getBlockAttrs($attrs);
        
        if (empty($blockAttrsJson)) {
            return '';
        }
        
        return self::getHtmlResponse(self::getShortcodeName() . '-' . self::$shortcodesCounter++,
            $blockAttrsJson
        );
    }
}
