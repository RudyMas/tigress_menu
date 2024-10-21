<?php

namespace Controller;

/**
 * Class Sidebar (PHP version 8.3)
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024 Rudy Mas (https://rudymas.be)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 1.2.2
 * @lastmodified 2024-10-21
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
    public function createSidebar(string $jsonFile): void
    {
        $this->menu = json_decode(file_get_contents(SYSTEM_ROOT . '/src/menus/' . $jsonFile), true);

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
     * @param $menuSidebarValue
     * @return void
     */
    public function addSidebarLeft($menuSidebarValue): void
    {
        $this->output .= '<div class="sidebar-left">';
        foreach ($menuSidebarValue as $menuPositionKey => $menuPostionValue) {
            if ($menuPositionKey === 'top') {
                $this->addSidebarTop($menuPostionValue);
            } elseif ($menuPositionKey === 'bottom') {
                $this->addSidebarBottom($menuPostionValue);
            }
        }
        $this->output .= '</div>';
    }

    /**
     * Add the right sidebar
     *
     * @param $menuSidebarValue
     * @return void
     */
    public function addSidebarRight($menuSidebarValue): void
    {
        $this->output .= '<div class="sidebar-right">';
        foreach ($menuSidebarValue as $menuPositionKey => $menuPostionValue) {
            if ($menuPositionKey === 'top') {
                $this->addSidebarTop($menuPostionValue);
            } elseif ($menuPositionKey === 'bottom') {
                $this->addSidebarBottom($menuPostionValue);
            }
        }
        $this->output .= '</div>';
    }

    /**
     * Add the top inside the sidebar
     *
     * @param $menuPositionValue
     * @return void
     */
    public function addSidebarTop($menuPositionValue): void
    {
        $this->output .= '<div class="sidebar-top"><ul>';
        $this->addSubmenu($menuPositionValue);
        $this->output .= '</ul></div>';
    }

    /**
     * Add the bottom inside the sidebar
     *
     * @param $menuPositionValue
     * @return void
     */
    public function addSidebarBottom($menuPositionValue): void
    {
        $this->output .= '<div class="sidebar-bottom"><ul>';
        $this->addSubmenu($menuPositionValue);
        $this->output .= '</ul></div>';
    }

    /**
     * Add the submenu
     *
     * @param $menuPositionValue
     * @return void
     */
    public function addSubmenu($menuPositionValue): void
    {
        foreach ($menuPositionValue as $menuItemValue) {
            if (isset($menuItemValue['type'])) {
                if ($menuItemValue['type'] === 'menu-item') {
                    $this->addMenuItem($menuItemValue);
                }
                if ($menuItemValue['type'] === 'back-button') {
                    $this->addBackButton($menuItemValue);
                }
            }
        }
    }

    /**
     * Add the menu item
     *
     * @param $menuItemValue
     * @return void
     */
    public function addMenuItem($menuItemValue): void
    {
        // remove spaces and non url characters from $menuItemValue['title']
        $toggleMenu = preg_replace('/[^a-zA-Z0-9]/', '', $menuItemValue['title']);

        $this->output .= '<li>';
        $this->output .= '<button class="menu-item" onclick="toggleMenu(\'' . $toggleMenu . '\')">' . $menuItemValue['title'] . '</button>';
        $this->output .= '<ul id="' . $toggleMenu . '" class="submenu">';
        foreach ($menuItemValue['children'] as $childItemValue) {
            if (isset($childItemValue['type'])) {
                if ($childItemValue['type'] === 'link') {
                    $this->addMenuLink($childItemValue);
                }
            }
        }
        $this->output .= '</ul>';
        $this->output .= '</li>';
    }

    /**
     * Add the back button
     *
     * @param $menuItemValue
     * @return void
     */
    public function addBackButton($menuItemValue): void
    {
        $text = $menuItemValue['title'] ?? 'Back';
        $align = $menuItemValue['align'] ?? 'left';
        $this->output .= '<li><button class="back-button" style="text-align: ' . $align . '" onclick="goBack(\'' . BASE_URL . $menuItemValue['path'] . '\')">' . $text . '</button></li>';
    }

    /**
     * Add the menu link
     *
     * @param $childItemValue
     * @return void
     */
    public function addMenuLink($childItemValue): void
    {
        $this->output .= '<li><a href="' . BASE_URL . $childItemValue['path'] . '"><i class="fa fa-' . $childItemValue['icon'] . '"></i> ' . $childItemValue['title'] . '</a></li>';
    }
}