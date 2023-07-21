<?php

namespace DmytroBezkrovnyi\SportsWidgets\Endpoints;

use WP_REST_Request;
use WP_REST_Response;

abstract class AbstractEndpoint
{
    protected static string $endpointBaseUrl = 'vcsw/v2';
    
    protected array $routeFields;
    
    protected static string $endpointUrl;
    
    /**
     * @return string
     */
    public static function getEndpointsBaseUrl() : string
    {
        return self::$endpointBaseUrl;
    }
    
    /**
     * @return string
     */
    public static function getEndpointUrl() : string
    {
        return static::$endpointUrl;
    }
    
    protected function getValidatedQueryParameters(array $incomingParams = []) : array
    {
        $returnParams = [];
        
        foreach ($incomingParams as $key => $item) {
            if (! array_key_exists($key, $this->routeFields)) {
                return [];
            }
            
            $returnParams[$key] = $item;
        }
        
        return $returnParams;
    }
    
    protected function isValidRequestReferrer(WP_REST_Request $request) : bool
    {
        $serverHost = ! empty($_SERVER['HTTP_HOST']) ? (string) $_SERVER['HTTP_HOST'] : null;
        $referrerHost = ! empty($request->get_header('host')) ? (string) $request->get_header('host') : null;
        
        return ! empty($serverHost) && ! empty($referrerHost) && $serverHost === $referrerHost;
    }
    
    protected function sendBadRequest(string $message = '') : WP_REST_Response
    {
        return new WP_REST_Response([
            'message' => $message
                ? : __('The required parameter is missing or invalid parameters specified.',
                    VCSW_DOMAIN
                )
        ], 400);
    }
    
    protected function sendBadPermissions(string $message = '') : WP_REST_Response
    {
        return new WP_REST_Response([
            'message' => $message ? : __('Sorry, you are not allowed to do that.', VCSW_DOMAIN)
        ], 401);
    }
    
    /**
     * Permission callback handler
     *
     * @return bool
     */
    public function permissionCallback() : bool
    {
        return true;
    }
    
    abstract public function registerEndpoint();
    
    abstract public function endpointHandler(WP_REST_Request $request);
}
