<?php

namespace DmytroBezkrovnyi\SportsWidgets\Service;

abstract class AbstractService
{
	abstract public static function getDataFromCore();
	abstract public static function syncDataWithCore();
	abstract public static function deleteAllData();
}