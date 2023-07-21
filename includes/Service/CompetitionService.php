<?php

namespace DmytroBezkrovnyi\SportsWidgets\Service;

use DmytroBezkrovnyi\SportsWidgets\Entity\SportType;
use DmytroBezkrovnyi\SportsWidgets\Model\CompetitionModel;
use DmytroBezkrovnyi\SportsWidgets\Request\Request;

class CompetitionService extends AbstractService
{
    public const CORE_ID_LENGTH = 24;
    
    public static function getDataFromCore() : array
    {
        $selectedSportTypes = SportType::getSelectedSportTypes();
        
        if (empty($selectedSportTypes)) {
            return [];
        }
        
        $startDate    = '2022-01-01'; // Take competitions from $startDate for NOW
        $merge_object = [];
        
        $filters = [
            'filters' => [
                'start_date' => [
                    '$gt' => $startDate
                ]
            ],
            'sort'    => ["competition"]
        ];
        
        foreach ($selectedSportTypes as $sportType) {
            $path = sprintf('api_route', $sportType);
            
            $response = Request::getInstance()->request($path, $filters);
            
            if ($response && $response['status'] === 200 && ! empty($response['response']['data']['items'])) {
                $merge_object = array_merge($merge_object, $response['response']['data']['items']);
            }
        }
        
        return $merge_object;
    }
    
    public static function syncDataWithCore() : bool
    {
        $competitions = self::getDataFromCore();
        
        if (empty($competitions)) {
            return false;
        }
        
        CompetitionModel::saveDataFromCore($competitions);
        
        return true;
    }
    
    public static function deleteAllData()
    {
        return CompetitionModel::truncateDatabaseTable();
    }
    
    public static function getCompetitionContent(string $compId = '') : string
    {
        return CompetitionModel::getCompetitionContent($compId);
    }
}
