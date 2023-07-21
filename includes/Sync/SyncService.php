<?php

namespace DmytroBezkrovnyi\SportsWidgets\Sync;

use DmytroBezkrovnyi\SportsWidgets\Service\BookmakerService;
use DmytroBezkrovnyi\SportsWidgets\Service\CompetitionService;
use DmytroBezkrovnyi\SportsWidgets\Service\MarketService;


class SyncService
{
	public static function sync()
	{
		CompetitionService::syncDataWithCore();
		BookmakerService::syncDataWithCore();
		MarketService::syncDataWithCore();
	}
}