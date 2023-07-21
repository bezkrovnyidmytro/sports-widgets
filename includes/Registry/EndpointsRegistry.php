<?php

namespace DmytroBezkrovnyi\SportsWidgets\Registry;

use DmytroBezkrovnyi\SportsWidgets\Endpoints\GetCompetitionStandings;
use DmytroBezkrovnyi\SportsWidgets\Endpoints\GetEventsByCompetition;
use DmytroBezkrovnyi\SportsWidgets\Endpoints\GetEventsByDate;
use DmytroBezkrovnyi\SportsWidgets\Endpoints\GetOdds;

class EndpointsRegistry
{
    public function registerAllEndpoints()
    {
        if(! defined('REST_REQUEST') || REST_REQUEST !== true) {
            return;
        }

        (new GetEventsByDate())->registerEndpoint();
        (new GetEventsByCompetition())->registerEndpoint();
        (new GetOdds())->registerEndpoint();
        (new GetCompetitionStandings())->registerEndpoint();
    }
}
