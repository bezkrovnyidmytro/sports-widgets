<?php

namespace DmytroBezkrovnyi\SportsWidgets\Service;

use DmytroBezkrovnyi\SportsWidgets\Model\BookmakerModel;
use DmytroBezkrovnyi\SportsWidgets\Request\Request;

class BookmakerService extends AbstractService
{
	/**
	 * Get Bookmakers from Core
	 *
	 * @return array
	 */
	public static function getDataFromCore(): array
	{
		$bookmakers = [];

        $response = Request::getInstance()->request('apiroute');

		if (! empty($response) && isset($response['response']['status']) && $response['response']['status'] === 'success') {
			$bookmakers = $response['response']['data']['items'];
		}

		return $bookmakers;
	}

	/**
	 * Sync Bookmakers with Core
	 *
	 * @return bool
     */
	public static function syncDataWithCore(): bool
	{
		$bookmakers = self::getDataFromCore();

		if(empty($bookmakers)) {
			return false;
		}

		// BookmakerModel::truncateDatabaseTable();
		BookmakerModel::saveDataFromCore($bookmakers);

		return true;
	}

    /**
     * Delete all bookmakers
     *
     * @return bool
     */
    public static function deleteAllData(): bool
    {
        return BookmakerModel::truncateDatabaseTable();
    }
}
