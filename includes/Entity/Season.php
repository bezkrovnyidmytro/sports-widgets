<?php

namespace DmytroBezkrovnyi\SportsWidgets\Entity;

class Season
{
    private string $year;

    /**
     * @return string
     */
    public function getYear(): string
    {
        return $this->year;
    }

    /**
     * @param string $year
     */
    public function setYear(string $year): void
    {
        $this->year = $year;
    }
}
