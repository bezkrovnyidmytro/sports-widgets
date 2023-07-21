<?php

namespace DmytroBezkrovnyi\SportsWidgets\Customization;

use DmytroBezkrovnyi\SportsWidgets\Model\StylesModel;

class StylesCustomizer
{
    private string $customStyles;
    
    public function __construct()
    {
        $this->customStyles = StylesModel::getCustomStyles();
    }
    
    /**
     * @return string
     */
    public function getCustomStyles() : string
    {
        return $this->customStyles;
    }
}
