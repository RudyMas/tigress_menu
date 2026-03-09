<?php

namespace Controller;

/**
 * Class Tiles (PHP version 8.5)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024-2026 Rudy Mas (https://rudymas.be)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2026.03.09.0
 * @package Tigress\Menu
 */
class Tiles extends Menu
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
     * Create a gray tile
     *
     * @param string $key
     * @param array $value
     * @return string
     */
    protected function createGrayTile(string $key, array $value): string
    {
        $output = "<a href='{$value['url']}' target='{$value['target']}' style='overflow: hidden; pointer-events: none; cursor: default'>";
        $output .= "<div class='{$value['button']} grey'>";
        $output .= "<i class='{$value['icon']} fa-2x' style='color: {$value['iconColor']}'></i>";
        $output .= "<span class='label'>{$key}</span>";
        $output .= '</div>';
        $output .= '</a>';
        return $output;
    }

    /**
     * Create a tile
     *
     * @param string $key
     * @param array $value
     * @param bool $showInfo
     * @return string
     */
    protected function createTile(string $key, array $value, bool $showInfo = false): string
    {
        $output = "<a href='{$value['url']}' target='{$value['target']}' style='overflow: hidden'>";
        if ($showInfo === true && !empty($value['info'])) {
            $output .= "<div class='{$value['button']} {$value['buttonColorClass']}' title='{$value['info']}' data-toggle='tooltip'>";
        } else {
            $output .= "<div class='{$value['button']} {$value['buttonColorClass']}'>";
        }
        $output .= "<i class='{$value['icon']} fa-2x' style='color: {$value['iconColor']}'></i>";
        $output .= "<span class='label'>{$key}</span>";
        $output .= '</div>';
        $output .= '</a>';
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
        $userAccessLevel = $_SESSION['user']['access_level'] ?? 1;
        $output = '<div class="home-tiles">';
        $output .= "<div class='row'>";
        foreach ($this->menu as $mainKey => $mainValue) {
            if ($this->checkLevel($mainValue, $userAccessLevel)) {
                $text_align = $mainValue['align'] ?? 'left';
                $backgroundColor = $mainValue['backgroundColor'] ?? '';
                $color = $mainValue['color'] ?? '';
                $output .= "<div class='col-lg-4 col-md-6 col-sm-12'>";
                $output .= "<h5 class='Start {$color} {$backgroundColor}' style='text-align: {$text_align}'>{$mainKey}</h5>";
                foreach ($mainValue['children'] as $key => $value) {
                    if ($this->checkLevel($value, $userAccessLevel)) {
                        if (RIGHTS->checkRightsForSpecificPath($value['url'])) {
                            $output .= $this->createTile($key, $value, $showInfo);
                        } else {
                            $output .= $this->createGrayTile($key, $value, $showInfo);
                        }
                    }
                }
                $output .= "</div>";
            }
        }
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
    private function buildTilesWithSidebar(bool $showInfo = false): string
    {
        return '<p>Not yet implemented!</p>';
    }

    /**
     * Check the access level of the user
     *
     * @param mixed $value
     * @param mixed $userAccessLevel
     * @return bool
     */
    private function checkLevel(mixed $value, mixed $userAccessLevel): bool
    {
        if (isset($value['level'])) {
            if (is_array($value['level'])) {
                $level = in_array($userAccessLevel, $value['level']);
            } else {
                $level = $value['level'] == $userAccessLevel;
            }
        } else {
            $level = true;
        }
        if (isset($value['levelRange'])) {
            $levelRange = $value['levelRange'];
            $level = ($userAccessLevel >= $levelRange[0] && $userAccessLevel <= $levelRange[1]);
        }
        return $level;
    }
}