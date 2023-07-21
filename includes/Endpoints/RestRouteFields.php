<?php

namespace DmytroBezkrovnyi\SportsWidgets\Endpoints;

use DmytroBezkrovnyi\SportsWidgets\Model\SportTypeModel;

class RestRouteFields
{
    private const CORE_ID_LENGTH = 24;

    public static function getSportTypeFieldArgs(): array
    {
        return [
            'type' => 'string',
            'required' => true,
            'description' => __('The sport type on which to query.', VCSW_DOMAIN),
            'validate_callback' => function($param) {
                return ! empty($param) && is_string($param);
            },
            'sanitize_callback' => function ($param) {
                return (string) $param;
            },
            'enum' => SportTypeModel::getSelected(),
        ];
    }

    public static function getCompetitionIdFieldArgs(): array
    {
        return [
            'type' => 'string',
            'required' => true,
            'description' => __('The unique ID of the competition from  API to query.', VCSW_DOMAIN),
            'validate_callback' => function($param) {
                return ! empty($param) && is_string($param) && strlen($param) === self::CORE_ID_LENGTH;
            },
            'sanitize_callback' => function ($param) {
                return (string) $param;
            }
        ];
    }

    public static function getLimitFieldArgs(): array
    {
        return [
            'type' => 'integer',
            'description' => __('Maximum number of items to be returned in result set.', VCSW_DOMAIN),
            'validate_callback' => function($param) {
                return ! empty($param) && is_int($param);
            },
            'sanitize_callback' => function($param) {
                return absint($param);
            },
        ];
    }

    public static function getPageFieldArgs(): array
    {
        return [
            'type' => 'integer',
            'description' => __('Current page of the collection.', VCSW_DOMAIN),
            'validate_callback' => function($param) {
                return ! empty($param) && is_int($param);
            },
            'sanitize_callback' => function($param) {
                return absint($param);
            },
        ];
    }

    public static function getFiltersFieldArgs(): array
    {
        return [
            'type' => 'object',
            'description' => __('Key value pairs of desired request parameters.'. VCSW_DOMAIN),
            'validate_callback' => function($param) {
                return ! empty($param) && is_array($param);
            },
            'sanitize_callback' => function($param) {
                return self::recursiveSanitizeField($param);
            },
        ];
    }

    public static function getSortFieldArgs(): array
    {
        return [
            'type' => 'array',
            'description' => __('Sort collection by object attribute.'. VCSW_DOMAIN),
            'validate_callback' => function($param) {
                return ! empty($param) && is_array($param);
            },
            'sanitize_callback' => function($param) {
                return self::recursiveSanitizeField($param);
            },
        ];
    }

    private static function recursiveSanitizeField($param): array
    {
        foreach ($param as &$value) {
            $value = is_array($value) ? self::recursiveSanitizeField($value) : sanitize_text_field($value);
        }

        return $param;
    }
}
