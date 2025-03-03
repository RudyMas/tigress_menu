<?php

namespace Controller;

/**
 * Class Tiles (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024 Rudy Mas (https://rudymas.be)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.03.03.0
 * @package Tigress\Menu
 */
class Tiles extends Menu
{
    /**
     * Create the tiles
     *
     * @param string $jsonFile
     * @param bool $withSidebar
     * @return string
     */
    public function createTiles(string $jsonFile, bool $withSidebar = false): string
    {
        $this->menu = json_decode(file_get_contents(SYSTEM_ROOT . '/src/menus/' . $jsonFile), true);

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
        $output = '<div class="home-tiles">';
        $output .= "<div class='row'>";
        foreach ($this->menu as $mainKey => $mainValue) {
            $output .= "<div class='col-lg-4 col-md-6 col-sm-12'>";
            $output .= "<h5 class='Start'>{$mainKey}</h5>";
            foreach ($mainValue['children'] as $key => $value) {
                if (RIGHTS->checkRightsForSpecificPath($value['url'])) {
                    $output .= $this->createTile($key, $value);
                } else {
                    $output .= $this->createGreyTile($key, $value);
                }
            }
            $output .= "</div>";
        }
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