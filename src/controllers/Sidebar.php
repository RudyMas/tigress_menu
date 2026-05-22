<?php

namespace Controller;

/**
 * Class Sidebar (PHP version 8.5)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024-2025 Rudy Mas (https://rudymas.be)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2026.05.22.0
 * @package Tigress\Menu
 */
class Sidebar extends Menu
{
    /**
     * Create the sidebar
     *
     * @param string $jsonFile
     * @param string|null $jsonFolder
     * @return void
     */
    public function createSidebar(string $jsonFile, ?string $jsonFolder = null): void
    {
        if ($jsonFolder === null) {
            $this->menu = json_decode(file_get_contents(SYSTEM_ROOT . '/src/menus/' . $jsonFile), true);
        } else {
            if (!str_starts_with($jsonFolder, '/')) {
                $jsonFolder = '/' . $jsonFolder;
            }
            if (!str_ends_with($jsonFolder, '/')) {
                $jsonFolder .= '/';
            }
            $this->menu = json_decode(file_get_contents(SYSTEM_ROOT . $jsonFolder . $jsonFile), true);
        }

        if (isset($this->menu['left'])) {
            $this->addSidebarLeft($this->menu['left']);
        }

        if (isset($this->menu['right'])) {
            $this->addSidebarRight($this->menu['right']);
        }
    }

    /**
     * Add the left sidebar
     *
     * @param mixed $menuSidebarValue
     * @return void
     */
    private function addSidebarLeft(mixed $menuSidebarValue): void
    {
        $this->output .= '<div class="sidebar-left">';
        foreach ($menuSidebarValue as $menuPositionKey => $menuPositionValue) {
            if ($menuPositionKey === 'top') {
                $this->addSidebarTop($menuPositionValue);
            } elseif ($menuPositionKey === 'bottom') {
                $this->addSidebarBottom($menuPositionValue);
            }
        }
        $this->output .= '</div>';
    }

    /**
     * Add the right sidebar
     *
     * @param mixed $menuSidebarValue
     * @return void
     */
    private function addSidebarRight(mixed $menuSidebarValue): void
    {
        $this->output .= '<div class="sidebar-right">';
        foreach ($menuSidebarValue as $menuPositionKey => $menuPositionValue) {
            if ($menuPositionKey === 'top') {
                $this->addSidebarTop($menuPositionValue);
            } elseif ($menuPositionKey === 'bottom') {
                $this->addSidebarBottom($menuPositionValue);
            }
        }
        $this->output .= '</div>';
    }

    /**
     * Add the top inside the sidebar
     *
     * @param mixed $menuPositionValue
     * @return void
     */
    private function addSidebarTop(mixed $menuPositionValue): void
    {
        $this->output .= '<div class="sidebar-top"><ul>';
        $this->addSubmenu($menuPositionValue);
        $this->output .= '</ul></div>';
    }

    /**
     * Add the bottom inside the sidebar
     *
     * @param mixed $menuPositionValue
     * @return void
     */
    private function addSidebarBottom(mixed $menuPositionValue): void
    {
        $this->output .= '<div class="sidebar-bottom"><ul>';
        $this->addSubmenu($menuPositionValue);
        $this->output .= '</ul></div>';
    }

    /**
     * Add the submenu
     *
     * @param mixed $menuPositionValue
     * @return void
     */
    private function addSubmenu(mixed $menuPositionValue): void
    {
        foreach ($menuPositionValue as $menuItemValue) {
            if (isset($menuItemValue['type'])) {
                switch ($menuItemValue['type']) {
                    case 'menu-item':
                        $this->addMenuItem($menuItemValue);
                        break;
                    case 'menu-link':
                        $this->addMenuLink($menuItemValue);
                        break;
                    case 'back-button':
                        $this->addBackButton($menuItemValue);
                        break;
                    default:
                        // Unknown type, you can log this if needed
                        break;
                }
            }
        }
    }

    /**
     * Add the menu item
     *
     * @param mixed $menuItemValue
     * @return void
     */
    private function addMenuItem(mixed $menuItemValue): void
    {
        // remove spaces and non url characters from $menuItemValue['title']
        $toggleMenu = preg_replace('/[^a-zA-Z0-9]/', '', $menuItemValue['title']);

        $this->output .= '<li>';
        $this->output .= '<button class="menu-item" onclick="toggleMenu(\'' . $toggleMenu . '\')">' . $menuItemValue['title'] . '</button>';
        $this->output .= '<ul id="' . $toggleMenu . '" class="submenu">';
        foreach ($menuItemValue['children'] as $childItemValue) {
            if (isset($childItemValue['type'])) {
                if ($childItemValue['type'] === 'link') {
                    $this->addSubmenuLink($childItemValue);
                }
            }
        }
        $this->output .= '</ul>';
        $this->output .= '</li>';
    }

    /**
     * Add the back button
     *
     * @param mixed $menuItemValue
     * @return void
     */
    private function addBackButton(mixed $menuItemValue): void
    {
        $text = $menuItemValue['title'] ?? 'Back';
        $align = $menuItemValue['align'] ?? 'left';
        $this->output .= '<li><button class="back-button" style="text-align: ' . $align . '" onclick="goBack(\'' . BASE_URL . $menuItemValue['path'] . '\')">' . $text . '</button></li>';
    }

    /**
     * Add the menu link
     *
     * @param mixed $menuItemValue
     * @return void
     */
    private function addMenuLink(mixed $menuItemValue): void
    {
        $this->output .= '<li><span class="menu-link"><a href="' . BASE_URL . $menuItemValue['path'] . '"><i class="' . $menuItemValue['icon'] . '"></i> ' . $menuItemValue['title'] . '</a></span></li>';
    }

    /**
     * Add the submenu link
     *
     * @param mixed $childItemValue
     * @return void
     */
    private function addSubmenuLink(mixed $childItemValue): void
    {
        $this->output .= '<li><a href="' . BASE_URL . $childItemValue['path'] . '"><i class="' . $childItemValue['icon'] . '"></i> ' . $childItemValue['title'] . '</a></li>';
    }
}