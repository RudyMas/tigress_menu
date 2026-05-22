<?php

namespace Controller;

/**
 * Class Tiles (PHP version 8.5)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025-2026 Rudy Mas (https://rudymas.be)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2026.05.22.0
 * @package Tigress\Menu
 */
class TilesOnly extends Tiles
{
    /**
     * Build the tiles
     *
     * @param bool $showInfo
     * @return string
     */
    protected function buildTiles(bool $showInfo = false): string
    {
        $output = '<div class="only-tiles">';
        $output .= "<div class='row'>";
        $output .= "<div class='col-sm-12'>";
        foreach ($this->menu['tiles'] as $key => $value) {
            if (RIGHTS->checkRightsForSpecificPath($value['url'])) {
                $output .= $this->createTile($key, $value, $showInfo);
            } else {
                $output .= $this->createGrayTile($key, $value);
            }
        }
        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }

    /**
     * Build the tiles with sidebar
     *
     * @param bool $showInfo
     * @return string
     */
    protected function buildTilesWithSidebar(bool $showInfo = false): string
    {
        return '<p>Not yet implemented!</p>';
    }
}