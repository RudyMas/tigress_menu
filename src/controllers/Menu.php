<?php

namespace Controller;

use Twig\Error\LoaderError;

/**
 * Class Menu (PHP version 8.3)
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024 Rudy Mas (https://rudymas.be)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 0.2.6
 * @lastmodified 2024-10-22
 * @package Tigress\Menu
 */
class Menu
{
    public array $menu = [];
    public string $output = '';

    /**
     * On which position the menu should be displayed
     * - top
     * - bottom
     * - both
     *
     * + SideMenu
     * - left
     * - right
     * - both
     *
     * @var string
     */
    private string $position = 'top';

    /**
     * Get the version of the DisplayHelper
     *
     * @return string
     */
    public static function version(): string
    {
        return '0.2.6';
    }

    /**
     * @throws LoaderError
     */
    public function __construct()
    {
        TWIG->addPath('vendor/tigress/menu/src/views');
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): void
    {
        $this->position = $position;
    }
}