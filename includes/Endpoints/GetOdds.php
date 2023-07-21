<?php

namespace DmytroBezkrovnyi\SportsWidgets\Endpoints;

use DmytroBezkrovnyi\SportsWidgets\Request\Request;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class GetOdds extends AbstractEndpoint
{
    private static string $baseUrl = '';

    protected static string $endpointUrl = 'odds';

    protected array $routeFields;

    public function __construct()
    {
        $this->routeFields = [
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

        if (empty($params)) {
            return self::sendBadRequest();
        }

        $response = Request::getInstance()->request(self::$baseUrl, $params);
        return Request::getInstance()->getRestFormattedResponse($response);
    }
}
