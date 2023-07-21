<?php

namespace DmytroBezkrovnyi\SportsWidgets\Service;

use DmytroBezkrovnyi\SportsWidgets\Request\Request;
use Exception;

class GeoLocationService
{
    private static ?array $location = null;
    
    /**
     * @throws Exception
     */
    public static function getUserGeoLocation(string $ip = '') : array
    {
        if (is_null(self::$location)) {
            
            $countryCode = '';
            $stateCode   = '';
            
            $cfLocation = self::getCloudFlareBasedLocation();
            
            if (! empty($cfLocation['country_code']) && ! empty($cfLocation['state_code'])) {
                $countryCode = $cfLocation['country_code'];
                $stateCode   = $cfLocation['state_code'];
            }
            
            if (empty($countryCode) || empty($stateCode)) {
                $apiLocation = self::getAPILocation($ip);
                
                if (! empty($apiLocation['country_code']) && ! empty($apiLocation['state_code'])) {
                    $countryCode = $apiLocation['country_code'];
                    $stateCode   = $apiLocation['state_code'];
                }
            }
            
            $countryCode = filter_var(strtolower($countryCode), FILTER_SANITIZE_STRING);
            $stateCode   = filter_var(strtolower($stateCode), FILTER_SANITIZE_STRING);
            
            self::$location['country_code']  = $countryCode;
            self::$location['state_code']    = $stateCode;
            self::$location['country_state'] = ($countryCode && $stateCode) ? $countryCode . '-' . $stateCode : 'n/a-n/a';
        }
        
        return self::$location;
    }
    
    /**
     * @return string[]
     */
    private static function getCloudFlareBasedLocation() : array
    {
        $countryCode = ! empty($_SERVER['HTTP_CF_IPCOUNTRY'])
            ? filter_var($_SERVER['HTTP_CF_IPCOUNTRY'], FILTER_SANITIZE_STRING)
            : '';
        
        $stateCode = ! empty($_SERVER['HTTP_CF_FIRST_SUBDIVISION'])
            ? filter_var($_SERVER['HTTP_CF_FIRST_SUBDIVISION'], FILTER_SANITIZE_STRING)
            : '';
        
        return ['country_code' => $countryCode, 'state_code' => $stateCode];
    }
    
    /**
     * @throws Exception
     */
    private static function getAPILocation(string $ip = '') : array
    {
        if (empty($ip)) {
            return ['country_code' => '', 'state_code' => ''];
        }
        
        $countryCode = '';
        $stateCode   = '';
        
        $path = 'geoip/' . $ip;
        
        $response = Request::getInstance()->request($path);
        
        if (! empty($response) && isset($response['response']['status']) && $response['response']['status'] === 'success') {
            $countryCode = ! empty($response['response']['data']['items'][0]['country']['code'])
                ? filter_var($response['response']['data']['items'][0]['country']['code'], FILTER_SANITIZE_STRING)
                : '';
            $stateCode   = ! empty($response['response']['data']['items'][0]['state']['code'])
                ? filter_var($response['response']['data']['items'][0]['state']['code'], FILTER_SANITIZE_STRING)
                : '';
        }
        
        return ['country_code' => $countryCode, 'state_code' => $stateCode];
    }
}
