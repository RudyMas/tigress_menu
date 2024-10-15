<?php

namespace Controller;

/**
 * Class Sidebar (PHP version 8.3)
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024 Rudy Mas (https://rudymas.be)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 1.1.1
 * @lastmodified 2024-10-15
 * @package Tigress\Menu
 */
class Sidebar extends Menu
{
    /**
     * Create the sidebar
     *
     * @param string $jsonFile
     * @return void
     */
    public function createSidebar(string $jsonFile = 'config/sidebar.json'): void
    {
        $this->menu = json_decode(file_get_contents($jsonFile));
    }
}