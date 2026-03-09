<?php

namespace Controller;

/**
 * Class Tiles (PHP version 8.5)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025-2026 Rudy Mas (https://rudymas.be)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2026.03.09.0
 * @package Tigress\Menu
 */
class TilesOnly extends Tiles
{
    /**
     * Create the tiles
     *
     * @param string|array $jsonFileOrArray
     * @param bool $withSidebar
     * @param bool $showInfo
     * @return string
     */
    public function createTiles(string|array $jsonFileOrArray, bool $withSidebar = false, bool $showInfo = false): string
    {
        if (file_exists(SYSTEM_ROOT . '/src/menus/' . $jsonFileOrArray)) {
            $this->menu = json_decode(file_get_contents(SYSTEM_ROOT . '/src/menus/' . $jsonFileOrArray), true);
        } else {
            $this->menu = json_decode($jsonFileOrArray, true);
        }

        if ($withSidebar === true) {
            $output = $this->buildTilesWithSidebar($showInfo);
        } else {
            $output = $this->buildTiles($showInfo);
        }

        return $output;
    }

    /**
     * Build the tiles
     *
     * @param bool $showInfo
     * @return string
     */
    private function buildTiles(bool $showInfo = false): string
    {
        $output = '<div class="only-tiles">';
        $output .= "<div class='row'>";
        $output .= "<div class='col-sm-12'>";
        foreach ($this->menu['tiles'] as $key => $value) {
            if (RIGHTS->checkRightsForSpecificPath($value['url'])) {
                $output .= $this->createTile($key, $value, $showInfo);
            } else {
                $output .= $this->createGrayTile($key, $value, $showInfo);
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
     * @return string
     */
    private function buildTilesWithSidebar(): string
    {
        return '<p>Not yet implemented!</p>';
    }
}