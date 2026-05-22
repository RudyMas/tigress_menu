# tigress_menu — Programmer's Manual

## Overview

`tigress_menu` is the Menu module of the **Tigress Framework** (PHP 8.5+). It renders two types of navigational UI components driven by JSON configuration files:

- **Tiles** — A grid of clickable tiles (icon + label) grouped into sections, for dashboard/homepage menus.
- **Sidebar** — A collapsible sidebar navigation with menu-items, links, submenus, and a back-button.

The module integrates with the Tigress core via global constants/objects (`TWIG`, `RIGHTS`, `SYSTEM_ROOT`, `BASE_URL`) and outputs HTML that relies on the included CSS grid layouts and JavaScript behavior.

---

## Class Hierarchy

```
Menu (base)
 ├── Tiles          → TilesOnly
 └── Sidebar
```

---

## Installation

```
composer require tigress/menu
```

Requires `php: >=8.5`.

The Twig view path is registered automatically in the `Menu` constructor:

```php
TWIG->addPath('vendor/tigress/menu/src/views');
```

---

## Base Class: `Menu` (`src/controllers/Menu.php`)

### Properties

| Property    | Type     | Description                            |
|-------------|----------|----------------------------------------|
| `$menu`     | `array`  | The parsed menu JSON data              |
| `$output`   | `string` | Accumulated HTML output                |
| `$position` | `string` | Navbar position: `top`, `bottom`, `none` |

### Methods

**`static version(): string`** — Returns the library version (`2026.03.09`).

**`getPosition(): string`** / **`setPosition(string $position): void`** — Get/set the navbar position.

---

## Tiles: `Tiles` (`src/controllers/Tiles.php`) and `TilesOnly`

### Usage

```php
$tiles = new Tiles();
$html = $tiles->createTiles('dashboard.json');   // loads from SYSTEM_ROOT/src/menus/dashboard.json
$html = $tiles->createTiles($jsonString);         // parses raw JSON string
$html = $tiles->createTiles($jsonString, true);   // unused sidebar flag (not yet implemented)
$html = $tiles->createTiles($jsonString, false, true);  // show info tooltips
```

### JSON Structure (Tiles format)

Loaded from `SYSTEM_ROOT/src/menus/<file>` or parsed inline.

```json
{
  "Group Name": {
    "align": "left",
    "backgroundColor": "bg-dark",
    "color": "text-white",
    "children": {
      "Item Label": {
        "url": "/some/path",
        "target": "_self",
        "icon": "fas fa-users",
        "iconColor": "#FFFFFF",
        "button": "btn-big",
        "buttonColorClass": "btn-primary",
        "info": "Optional tooltip text",
        "level": 1,
        "levelRange": [1, 3]
      }
    }
  }
}
```

#### Supported fields per tile item

| Field              | Type            | Required | Description                                        |
|--------------------|-----------------|----------|----------------------------------------------------|
| `url`              | `string`        | **Yes**  | Target URL                                         |
| `target`           | `string`        | Yes      | `_self`, `_blank`, etc.                            |
| `icon`             | `string`        | Yes      | Font Awesome icon class (or similar)               |
| `iconColor`        | `string`        | Yes      | CSS color for the icon                             |
| `button`           | `string`        | Yes      | CSS class: `btn-big` or `btn-small`                |
| `buttonColorClass` | `string`        | No       | Additional color class for the button              |
| `info`             | `string`        | No       | Shown as tooltip when `$showInfo` is `true`        |
| `level`            | `int` / `int[]` | No       | Required access level (single value or array)      |
| `levelRange`       | `int[2]`        | No       | Access level range `[min, max]` (overrides `level` |

#### Section-level fields

| Field             | Type     | Description                           |
|-------------------|----------|---------------------------------------|
| `align`           | `string` | Text alignment: `left`, `center`, `right` |
| `backgroundColor` | `string` | Background color class                |
| `color`           | `string` | Text color class                      |

### Access Control

1. **Level check** — If `level` or `levelRange` is set on a section or item, it is compared against `$_SESSION['user']['access_level']`. Items/sections the user cannot see are skipped entirely.
2. **Rights check** — `RIGHTS->checkRightsForSpecificPath($url)` is called per item. If the user lacks rights, the tile is rendered **grayed out** (via `createGrayTile`) instead of being hidden.

### `TilesOnly`

`TilesOnly extends Tiles` and renders a flat (ungrouped) tile grid. Its JSON expects a `tiles` key:

```json
{
  "tiles": {
    "Item Label": {
      "url": "...",
      "target": "...",
      "icon": "...",
      "iconColor": "...",
      "button": "btn-big",
      "buttonColorClass": "...",
      "info": "..."
    }
  }
}
```

Access control per item matches `Tiles`.

---

## Sidebar: `Sidebar` (`src/controllers/Sidebar.php`)

### Usage

```php
$sidebar = new Sidebar();
$sidebar->createSidebar('sidebar.json');
// or with custom folder:
$sidebar->createSidebar('sidebar.json', '/src/config');
```

The sidebar JSON supports **left** and **right** sidebars, each with a **top** zone and a **bottom** zone.

```json
{
  "left": {
    "top": [ ... ],
    "bottom": [ ... ]
  },
  "right": {
    "top": [ ... ],
    "bottom": [ ... ]
  }
}
```

### Item Types

Each item in a zone array must have a `type` field:

#### `menu-item`

A collapsible section with child links. Children with `type: "link"` become sub-navigation.

```json
{
  "type": "menu-item",
  "title": "Management",
  "children": [
    {
      "type": "link",
      "title": "Users",
      "path": "/admin/users",
      "icon": "fas fa-user"
    }
  ]
}
```

The title is sanitized to alphanumeric chars and used as the HTML `id` for the submenu `<ul>`. JavaScript toggles it via `toggleMenu(id)`.

#### `menu-link`

A direct link displayed in the sidebar.

```json
{
  "type": "menu-link",
  "title": "Dashboard",
  "path": "/dashboard",
  "icon": "fas fa-home"
}
```

#### `back-button`

A button that navigates the user back to a given URL.

```json
{
  "type": "back-button",
  "title": "Back to Dashboard",
  "path": "/dashboard",
  "align": "center"
}
```

The generated button calls `goBack('BASE_URL + path')`. Title defaults to `"Back"`; align defaults to `"left"`.

---

## Twig Templates

### `menu_grid_css.twig`

Conditionally includes the correct grid-layout CSS and sidebar CSS files based on `menu.position` and `sidebar.position`. Supports all 12 combinations:

| Menu    | Sidebar    | Grid CSS                     | Sidebar CSS          |
|---------|------------|------------------------------|----------------------|
| top     | (none)     | `grid-top-none.css`          | —                    |
| top     | left       | `grid-top-left.css`          | `sidebar-left.css`   |
| top     | right      | `grid-top-right.css`         | `sidebar-right.css`  |
| top     | both       | `grid-top-both.css`          | both sidebar CSS     |
| bottom  | —          | `grid-bottom-*` variants     | —                    |
| (none)  | —          | `grid-none-*` variants       | —                    |

It expects Twig variables `menu` and `sidebar` (each with a `.position` property).

### `sidebar.twig`

Simply outputs `{{ sidebar.output|raw }}`.

### `menu.twig`

Currently **empty** — intended for rendering the tile menu HTML.

---

## CSS: Grid Layout Files

All 14 CSS files in `public/css/` define a `.grid-container` using CSS Grid:

| File                    | Navbar  | Columns                     |
|-------------------------|---------|-----------------------------|
| `grid-top-*.css`        | `nav`   | 1–3 columns depending on sidebar |
| `grid-bottom-*.css`     | `nav`   | Same, navbar at bottom      |
| `grid-none-*.css`       | —       | Same, no navbar             |

`grid-top-none.css` applies `position: sticky` to `.navbar`; the bottom variants swap `header` and `footer` roles with the navbar.

### Shared classes

- `.home-tiles` — Centered container (80% width) for grouped tiles.
- `.only-tiles` — Full-width container for flat tiles.
- `.btn-big` (200px max) / `.btn-small` (95px max) — Tile button sizes.
- `.label` — Bottom-left label on each tile.
- `.sidebar-left` / `.sidebar-right` — Flexbox column containers.
- `.submenu` — Hidden by default, toggled via `.active` class.
- `.menu-item.active`, `.menu-link a.active`, `.submenu li a.active` — Green highlight for active items.
- `.sidebar-bottom` — Pushes content to bottom via `margin-top: auto`.

---

## JavaScript (`public/javascript/menu.js`)

### `toggleMenu(id)`

Toggles the `active` class on a submenu `<ul>` by its `id`. Used by `menu-item` buttons.

### `goBack(url)`

Sets `window.location` to the given URL. Used by `back-button` items.

### `setActiveMenu()`

Runs on `window.onload`. Walks all sidebar links and matches their `href` against `window.location.pathname` (segment-by-segment prefix match). When a match is found:

- The link gets `class="active"`.
- Its parent `.submenu` gets `class="active"` (making it visible).
- The section button (`.submenu`'s previous sibling) gets `class="active"`.

Both `.sidebar-left` and `.sidebar-right` are processed.

---

## Known Issues / Notes

1. **`buildTilesWithSidebar()` is not implemented** in both `Tiles` and `TilesOnly` — returns `'<p>Not yet implemented!</p>'`.
2. **Bug in `Tiles::buildTiles()`** (`src/controllers/Tiles.php:105`): calls `$this->createGrayTile($key, $value, $showInfo)` with 3 arguments, but `createGrayTile(string $key, array $value)` only accepts 2. The extra `$showInfo` argument is silently ignored in PHP, but this is a latent defect.
3. **JSON loading code is duplicated** between `Tiles::createTiles()` and `TilesOnly::createTiles()`.
4. **`menu.twig` is empty** — the tile HTML output must be passed to a template or echoed directly.
5. **`Sidebar` methods** (`addSidebarLeft`, `addSidebarRight`, `addSubmenu`, etc.) are `public` but designed as internal helpers; they could be marked `protected` or `private`.
6. **Duplicate submenu IDs** — `toggleMenu()` uses `document.getElementById(id)`. If two `menu-item` entries have the same title, their sanitized IDs collide. Titles should be unique within a sidebar.
7. **No PHP 8.5 type hints** on some `Sidebar` parameters (uses `mixed`/untyped).
8. **Version inconsistency**: `Sidebar.php` declares `PHP 8.4`, while other files declare `8.5`.
