<?php

namespace DmytroBezkrovnyi\SportsWidgets\Entity;

use DmytroBezkrovnyi\SportsWidgets\Model\SportTypeModel;

class SportType
{
    public static array $sportTypes = ['football', 'volleyball'];

    /**
     * @return array
     */
    public static function getSelectedSportTypes(): array
    {
		return SportTypeModel::getSelected();
    }

    /**
     * @param array $sportTypes
     */
    public static function setSportTypes(array $sportTypes): void
    {
        self::$sportTypes = $sportTypes;
    }
}
