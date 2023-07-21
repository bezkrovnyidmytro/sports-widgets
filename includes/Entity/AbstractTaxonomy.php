<?php

namespace DmytroBezkrovnyi\SportsWidgets\Entity;

/**
 *
 */
abstract class AbstractTaxonomy
{
    protected string $name;
    
    protected static string $slug;
    
    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }
    
    abstract protected function registerTaxonomy();
    
    abstract protected function getArgs();
}
