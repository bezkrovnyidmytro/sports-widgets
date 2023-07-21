<?php

namespace DmytroBezkrovnyi\SportsWidgets\Service;

use DmytroBezkrovnyi\SportsWidgets\Model\MarketModel;
use DmytroBezkrovnyi\SportsWidgets\Request\Request;

class MarketService extends AbstractService
{
    public static function getDataFromCore() : array
    {
        // all slugs for markets must match with markets from  Core
        $marketsList = [
            [
                'sport_type' => 'football',
                'name'       => 'Full Time 1X2',
                'slug'       => '1X2',
            ],
            [
                'sport_type' => 'football',
                'name'       => 'Half Time 1X2',
                'slug'       => 'HT 1X2',
            ],
            [
                'sport_type' => 'football',
                'name'       => 'Over/Under',
                'slug'       => 'Over/Under',
            ],
            [
                'sport_type' => 'football',
                'name'       => 'Goal/No Goal',
                'slug'       => 'Goal/No Goal',
            ],
            [
                'sport_type' => 'football',
                'name'       => 'First Team To Score',
                'slug'       => 'First Team To Score',
            ],
            [
                'sport_type' => 'football',
                'name'       => 'Double Chance',
                'slug'       => 'Double Chance',
            ],
        ];
        
        $mergeMarket = [];
        $path = 'api route path';
        
        if (! empty($marketsList)) {
            foreach ($marketsList as $market) {
                $data = [
                    'filters' => sprintf('{"market":"%s"}', rawurlencode($market['slug'])),
                    'limit'   => '1',
                ];
                
                $response = Request::getInstance()->request($path, $data);
                
                if ($response && ! empty($response['response']['data']['items'][0]['market'])
                    && is_string($response['response']['data']['items'][0]['market'])) {
                    $market['name'] = $response['response']['data']['items'][0]['market'];
                }
                
                $mergeMarket[] = $market;
            }
        }
        
        return $mergeMarket;
    }
    
    public static function syncDataWithCore() : bool
    {
        $markets = self::getDataFromCore();
        
        if (empty($markets)) {
            return false;
        }
        
        // MarketModel::truncateDatabaseTable(); // remove all markets and sync again
        MarketModel::saveDataFromCore($markets);
        
        return true;
    }
    
    public static function deleteAllData()
    {
        return MarketModel::truncateDatabaseTable();
    }
}
