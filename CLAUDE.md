# CLAUDE.md

## What this is

**Element Pack Addon** — companion plugin for **Element Pack Pro** (`../bdthemes-element-pack`). It carries the 85 legacy widgets that were removed from Pro (post-*, crypto-currency-*, bbPress, BuddyPress, Charitable, Give, EDD, and a handful of general widgets). It has no module loader, admin UI, or asset pipeline of its own beyond the build; everything is delegated to Pro.

Requires Element Pack Pro >= `BDTEPA_REQUIRED_EP_VER` (the version that added the companion-module hooks). PHP lives under the `ElementPackAddon` namespace. Text domain is intentionally `bdthemes-element-pack` so Pro's translations apply.

## How it plugs into Pro

Two filters, registered at file load in `bdthemes-element-pack-addon.php`:

- `element_pack/modules/external_sources` — maps every slug in `includes/modules-list.php` to `['dir' => BDTEPA_PATH, 'url' => BDTEPA_URL, 'namespace' => 'ElementPackAddon', 'version' => BDTEPA_VER]`. Pro's `ModuleService::get_module_dir()`, `Manager::load_module_instance()` and the asset optimizer use this to find `module.info.php`, the `Module` class and `assets/css|js/modules/ep-<slug>.*`.
- `element_pack/modules/settings_fields` — appends the entries from `Modules_List::get_settings_fields()` into Pro's three tiers, so the widgets show up in Pro's dashboard, permission manager and optimizer with their original option keys.

`loader.php` (`ElementPackAddon\Loader`) adds the autoloader, vendor scripts only our widgets need, the `ep-addon-styles` / `ep-addon-scripts` editor bundles, the `element-pack-bbpress` category and WPML nodes (`includes/class-wpml.php`).

## Modules

`modules/<slug>/` is byte-identical to what Pro shipped, except:
- `namespace ElementPack\Modules\X` → `namespace ElementPackAddon\Modules\X` (all other `use ElementPack\...` imports still point at Pro's base classes, traits and helpers).
- The edit-mode branch of `get_style_depends()` / `get_script_depends()` returns `ep-addon-styles` / `ep-addon-scripts` instead of `ep-styles` / `ep-scripts`.
- The `register_deprecated_*_widget_controls()` calls were removed.

Widget `get_name()` values (`bdt-post-grid` …) and option keys must never change — saved pages and existing dashboard toggles depend on them.

## Build

```bash
npm install
npm run build   # less + concat + terser + copy -> assets/
npm run dev     # same, then watch
npm run zip     # build, then stage per .buildignore and zip
```

Sources: `src/css/widgets/*.css`, `src/css/third-party-widgets/*.css`, `src/frontend/js/modules/ep-*.js`, `src/vendor/js/`. Never edit `assets/` by hand. `src/editor/js/ep-addon-scripts.js` is a generated concat file (git-ignored).

## Adding a module

Copy the folder from Pro, apply the three transformations above, add its settings entry to `includes/modules-list.php`, add its CSS/JS to `src/`, rebuild. Pro's `element-pack-filters.php` helpers (`element_pack_is_widget_enabled()` etc.) work unchanged for addon slugs.
