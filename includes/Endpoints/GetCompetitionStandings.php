<?php

namespace DmytroBezkrovnyi\SportsWidgets\Endpoints;

use DmytroBezkrovnyi\SportsWidgets\Request\Request;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class GetCompetitionStandings extends AbstractEndpoint
{
    private static string $baseUrl = '';

    protected static string $endpointUrl = 'competition-standings/(?P<sports_type>[\w]+)/(?P<competition_id>[\w]+)/';

    protected array $routeFields;

    public function __construct()
    {
        $this->routeFields = [
            'sports_type' => RestRouteFields::getSportTypeFieldArgs(),
            'competition_id' => RestRouteFields::getCompetitionIdFieldArgs(),
        ];
    }

    public function registerEndpoint()
    {
        register_rest_route(
            self::$endpointBaseUrl,
            self::$endpointUrl,
            [
                'methods' => WP_REST_Server::READABLE,
                'callback' => [$this, 'endpointHandler'],
                'permission_callback' => [$this, 'permissionCallback'],
                'args' => $this->routeFields
            ]
        );
    }

    public function endpointHandler(WP_REST_Request $request): WP_REST_Response
    {
        if(self::isValidRequestReferrer($request) !== true) {
            return self::sendBadPermissions();
        }

        $params = $request->get_params();

        if (empty($params)) {
            return self::sendBadRequest();
        }

        $baseUrl = sprintf(self::$baseUrl, $params['sports_type'], $params['competition_id']);
        unset($params['sports_type']);

        $response = Request::getInstance()->request($baseUrl, $params);
        return Request::getInstance()->getRestFormattedResponse($response);
    }
}
