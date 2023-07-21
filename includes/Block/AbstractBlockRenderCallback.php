<?php

namespace DmytroBezkrovnyi\SportsWidgets\Block;

/**
 * Class AbstractBlockRenderCallback
 */
abstract class AbstractBlockRenderCallback
{
    /**
     * @param array $attributes
     * @return string
     */
    abstract public static function render(array $attributes): string;
}
