<?php

namespace DmytroBezkrovnyi\SportsWidgets\Block\LeagueStandings;

use DmytroBezkrovnyi\SportsWidgets\Entity\AbstractBlock;

class LeagueStandingsBlock extends AbstractBlock
{
    protected static string $blockName = 'league-standings-block';
    
    protected static string $blockTitle = 'League Standings';
    
    protected static array $defaultArgs = [
        'sports_type' => 'football',
        'competition' => '',
        'block_id'    => '',
    ];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public static function render(array $attrs) : string
    {
        // if competition is empty - nothing to show and return from func
        if (empty($attrs['competition'])) {
            return '';
        }
        
        $blockAttrsJson = self::getBlockAttrs($attrs);
        
        if (empty($blockAttrsJson)) {
            return '';
        }
        
        return self::getHtmlResponse($attrs['block_id'], $blockAttrsJson);
    }
}
