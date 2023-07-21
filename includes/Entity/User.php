<?php

namespace DmytroBezkrovnyi\SportsWidgets\Entity;

class User
{
    private string $ip;
    
    public function __construct()
    {
        $this->ip = $this->getUserIp();
    }
    
    private function getUserIp() : string
    {
        foreach (
            [
                'HTTP_CLIENT_IP',
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'REMOTE_ADDR'
            ] as $key
        ) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                    $ip = filter_var($ip,
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                    ) ? : false;
                    
                    if (! empty($ip)) {
                        return $ip;
                    }
                }
            }
        }
        
        return '';
    }
    
    /**
     * @return string
     */
    public function getIp() : string
    {
        return $this->ip;
    }
}
