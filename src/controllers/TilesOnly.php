<?php

namespace Controller;

/**
 * Class Tiles (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025 Rudy Mas (https://rudymas.be)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.09.22.0
 * @package Tigress\Menu
 */
class TilesOnly extends Menu
{
    /**
     * Create the tiles
     *
     * @param string|array $jsonFileOrArray
     * @param bool $withSidebar
     * @return string
     */
    public function createTiles(string|array $jsonFileOrArray, bool $withSidebar = false): string
    {
        if (file_exists(SYSTEM_ROOT . '/src/menus/' . $jsonFileOrArray)) {
            $this->menu = json_decode(file_get_contents(SYSTEM_ROOT . '/src/menus/' . $jsonFileOrArray), true);
        } else {
            $this->menu = json_decode($jsonFileOrArray, true);
        }

        if ($withSidebar === true) {
            $output = $this->buildTilesWithSidebar();
        } else {
            $output = $this->buildTiles();
        }

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

    /**
     * Build the tiles
     *
     * @return string
     */
    private function buildTiles(): string
    {
        $output = '<div class="only-tiles">';
        $output .= "<div class='row'>";
        $output .= "<div class='col-sm-12'>";
        foreach ($this->menu['tiles'] as $key => $value) {
            if (RIGHTS->checkRightsForSpecificPath($value['url'])) {
                $output .= $this->createTile($key, $value);
            } else {
                $output .= $this->createGreyTile($key, $value);
            }
        }
        $output .= "</div>";
        $output .= "</div>";
        $output .= "</div>";

        return $output;
    }

    /**
     * Create a tile
     *
     * @param string $key
     * @param array $value
     * @return string
     */
    private function createTile(string $key, array $value): string
    {
        $output = "<a href='{$value['url']}' target='{$value['target']}' style='overflow: hidden'>";
        $output .= "<div class='{$value['button']} {$value['buttonColorClass']}'>";
        $output .= "<i class='{$value['icon']} fa-2x' style='color: {$value['iconColor']}'></i>";
        $output .= "<span class='label'>{$key}</span>";
        $output .= '</div>';
        $output .= '</a>';
        return $output;
    }

    /**
     * Create a grey tile
     *
     * @param string $key
     * @param array $value
     * @return string
     */
    private function createGreyTile(string $key, array $value): string
    {
        $output = "<a href='{$value['url']}' target='{$value['target']}' style='overflow: hidden; pointer-events: none; cursor: default'>";
        $output .= "<div class='{$value['button']} grey'>";
        $output .= "<i class='{$value['icon']} fa-2x' style='color: {$value['iconColor']}'></i>";
        $output .= "<span class='label'>{$key}</span>";
        $output .= '</div>';
        $output .= '</a>';
        return $output;
    }
}