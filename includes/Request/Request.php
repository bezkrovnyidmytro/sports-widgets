<?php
/**
 * API request class: Request
 *
 * Handles all the internal stuff to form and process a proper API request.
 *
 * @package    sports-widgets
 * @subpackage sports-widgets/includes/Request.
 */

namespace DmytroBezkrovnyi\SportsWidgets\Request;

use DmytroBezkrovnyi\SportsWidgets\PluginI18N;
use Exception;
use WP_REST_Response;

if (! defined('WPINC')) {
    die;
}

/**
 * Class Request.
 */
class Request
{
    public static string $optionName = 'vcsw_core_data_source';
    
    public static int $cacheLifeTime = 900;
    
    private static ?Request $instance = null;
    
    private static string $stagingApiUrl = '';
    
    private static string $productionApiUrl = '';
    
    private string $baseUrl;
    
    private array $requestData; // cache lifetime in seconds, 15 minutes == 900 seconds
    
    /**
     * Request constructor.
     * Init all Request properties in PluginMain->initRequest()
     *
     * @throws Exception  Init exception.
     * @since 3.0
     */
    public function __construct()
    {
        $this->baseUrl     = self::getSelectedDataSourceUrl();
        $this->requestData = [
            'lang' => PluginI18N::getSelectedLang()
        ];
    }
    
    public static function getSelectedDataSourceUrl() : string
    {
        return self::getSelectedDataSource() === 'production'
            ? self::$productionApiUrl
            : self::$stagingApiUrl;
    }
    
    public static function getSelectedDataSource() : string
    {
        return get_option(self::$optionName, self::getDefaultDataSource()) ? : self::getDefaultDataSource();
    }
    
    public static function getDefaultDataSource() : string
    {
        return 'production';
    }
    
    public static function getInstance() : ?Request
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    public static function getDataSources() : array
    {
        return [
            'staging'    => 'Staging',
            'production' => 'Production',
        ];
    }
    
    public function request(string $path, array $data = [], string $method = 'get') : array
    {
        $url  = $this->getApiUrl($path);
        $data = array_filter(array_merge($this->requestData, $data));
        
        if (strtolower($method) === 'get' && $data) {
            $urlQueryArgs = '?';
            
            foreach ($data as $key => $value) {
                $param        = is_array($value) && ! empty($value) ? urlencode(json_encode($value)) : $value;
                $urlQueryArgs .= $key && $param ? "$key=$param&" : '';
            }
            
            $url .= $urlQueryArgs;
        }
        
        return $this->getRequest($url);
    }
    
    /**
     * Get API endpoint URL for request.
     *
     * @param string $path Endpoint path.
     *
     * @return string
     * @since 3.0
     *
     */
    private function getApiUrl(string $path = '') : string
    {
        return trailingslashit($this->baseUrl . $path);
    }
    
    private function getRequest(string $url = '') : array
    {
        if (! $url) {
            return [
                'status'     => 400,
                'response'   => [],
                'message'    => __('Empty URL provided.', VCSW_DOMAIN),
                'total_time' => 0,
            ];
        }
        
        $errMsg = '';
        
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true, // it will return the result on success
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TCP_FASTOPEN   => true,
            CURLOPT_HTTPHEADER     => [
                'Cache-Control: public, max-age=' . self::$cacheLifeTime . ', must-revalidate;',
                'Content-Type: application/json',
            ],
        ]);
        
        $response = curl_exec($ch);
        
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $totalTime  = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        
        if (! empty($response)) {
            $response = json_decode($response, true);
        }
        else {
            $response = [];
            $errMsg   = 'cURL error (' . curl_errno($ch) . '): ' . curl_error($ch);
        }
        
        curl_close($ch);
        
        return [
            'status'     => $httpStatus,
            'response'   => $response,
            'message'    => $errMsg,
            'total_time' => $totalTime,
        ];
    }
    
    public function getRestFormattedResponse(array $response = []) : WP_REST_Response
    {
        $response = $this->getFormattedResponse($response);
        $response = new WP_REST_Response($response, 200);
        // Set headers for caching purposes
        $response->set_headers(['Cache-Control' => 'public, max-age=' . self::$cacheLifeTime . ', must-revalidate;']);
        
        return $response;
    }
    
    public function getFormattedResponse(array $response = []) : array
    {
        $itemsToReturn      = [];
        $countItemsToReturn = 0;
        $messages           = [];
        
        if (is_array($response['response']['data']['items']) && ! empty($response['response']['data']['items'])) {
            $itemsToReturn      = $response['response']['data']['items'];
            $countItemsToReturn = is_countable($itemsToReturn) ? count($itemsToReturn) : 0;
        }
        
        if (! empty($response['message'])) {
            // First message from the PHP CURL handler
            $messages [] = $response['message'];
        }
        if (! empty($response['response']['message'])) {
            // Second message from the  API
            $messages [] = $response['response']['message'];
        }
        
        return [
            'status'      => $response['status'],
            'message'     => implode('; ', $messages),
            'items'       => $itemsToReturn,
            'items_count' => $countItemsToReturn,
            'total_time'  => $response['total_time']
        ];
    }
}
