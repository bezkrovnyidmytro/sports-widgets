<?php

namespace DmytroBezkrovnyi\SportsWidgets\Block\SportsFixtures;

use DmytroBezkrovnyi\SportsWidgets\Entity\AbstractBlock;

class SportsFixturesBlock extends AbstractBlock
{
    protected static string $blockName = 'sports-fixtures-block';
    
    protected static string $blockTitle = 'Sports Fixtures';
    
    protected static array $defaultArgs = [
        'sports_type' => 'football',
        'competition' => '',
        'block_id'    => '',
        'date'        => '',
    ];
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public static function render(array $attrs) : string
    {
        $blockAttrsJson = self::getBlockAttrs($attrs);
        
        if (empty($blockAttrsJson)) {
            return '';
        }
        
        return self::getHtmlResponse($attrs['block_id'], $blockAttrsJson);
    }
}
