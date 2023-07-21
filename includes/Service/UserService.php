<?php

namespace DmytroBezkrovnyi\SportsWidgets\Service;

use DmytroBezkrovnyi\SportsWidgets\Entity\User;
use Exception;

class UserService
{
    private ?User $user;
    
    private ?array $userLocation;
    
    /**
     * @throws Exception
     */
    public function __construct(User $user)
    {
        $userIP = $user->getIp();
        
        $this->user         = $user;
        $this->userLocation = GeoLocationService::getUserGeoLocation($userIP);
    }
    
    public function getUserLocation() : ?array
    {
        return $this->userLocation;
    }
    
    public function addUserLocationToBodyClass($classes)
    {
        if (! empty($this->userLocation)) {
            return array_merge($classes, $this->userLocation);
        }
        
        return $classes;
    }
    
    /**
     * @return User
     */
    public function getUser() : User
    {
        return $this->user;
    }
}
