<?php

namespace DmytroBezkrovnyi\SportsWidgets\Endpoints;

use DmytroBezkrovnyi\SportsWidgets\Request\Request;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class GetEventsByCompetition extends AbstractEndpoint
{
    private static string $baseUrl = '';

    protected static string $endpointUrl = 'events-by-competition';

    protected array $routeFields;

    public function __construct()
    {
        $this->routeFields = [
            'sports_type' => RestRouteFields::getSportTypeFieldArgs(),
            'competition_id' => RestRouteFields::getCompetitionIdFieldArgs(),
            'filters' => RestRouteFields::getFiltersFieldArgs(),
            'sort' => RestRouteFields::getSortFieldArgs(),
            'limit' => RestRouteFields::getLimitFieldArgs(),
            'page' => RestRouteFields::getPageFieldArgs(),
        ];
    }

    public function registerEndpoint()
    {
        register_rest_route(
            self::$endpointBaseUrl,
            self::$endpointUrl,
            [
                'methods' => WP_REST_Server::CREATABLE,
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

        $requestBody = $request->get_json_params();

        if(empty($requestBody)) {
            return self::sendBadRequest();
        }

        $params = $this->getValidatedQueryParameters($requestBody);

        if (empty($params)
            || empty($params['sports_type'])
            || empty($params['competition_id'])
        ) {
            return self::sendBadRequest();
        }

        $baseUrl = sprintf(self::$baseUrl, $params['sports_type'], $params['competition_id']);
        unset($params['sports_type']);

        $response = Request::getInstance()->request($baseUrl, $params);
        return Request::getInstance()->getRestFormattedResponse($response);
    }
}
