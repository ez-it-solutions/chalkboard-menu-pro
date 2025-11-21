# Chalkboard Menu Pro

Beautiful chalkboard-style menu boards for WordPress, built by **Ez IT Solutions**.

This plugin makes it easy to display a framed chalkboard menu on any page using a shortcode, with a roadmap that includes drag-and-drop builders, multiple layouts, and deep page-builder integration.

> **Status:** Early prototype (v0.1.0) – shortcode and admin landing page only.

---

## Features (v0.1.0)

- **Chalkboard menu shortcode** that approximates a classic cafe menu layout.
- **Responsive three-column layout** that collapses gracefully on mobile.
- **Admin dashboard page** under `Chalkboard Menu` with Ez IT Solutions styling hooks.
- **Extensible architecture** ready for presets, drag-and-drop item builders, and Divi/Elementor modules.

---

## Installation

1. Download or clone this repository into your WordPress `wp-content/plugins` directory as `chalkboard-menu-pro`.
2. Ensure the main plugin file is named `chalkboard-menu-pro.php`.
3. In your WordPress admin, go to **Plugins → Installed Plugins**.
4. Activate **Chalkboard Menu Pro**.

---

## Usage

### Shortcode

Use the shortcode in any page, post, or custom post type:

```text
[chalkboard_menu_pro]
```

This renders a sample chalkboard menu with several columns (Espresso, Tea, Specialty, Coffee, Smoothies, Extras) using a dark chalkboard background and decorative border effect.

In early versions the menu content and layout are **static** and intended as a visual reference. Future versions will allow you to:

- Choose from multiple **board frames** and chalkboard textures.
- Define **menu sections and items** from the admin dashboard.
- Re-order sections/items using a **drag-and-drop interface**.
- Save and reuse **presets** across multiple pages.

---

## Admin Dashboard

After activation you will find a new top-level menu item:

- **Chalkboard Menu → Chalkboard Menu Pro**

The initial dashboard provides:

- **Getting Started** steps.
- Basic Ez IT Solutions card-based layout with light/dark theme hooks.

The dashboard is designed to grow into a **tabbed interface** for presets, layouts, integrations, and advanced styling.

---

## Roadmap

- **Dynamic menu builder** with sections and items stored in the database.
- **Preset styles** (classic frame, minimalist, chalk sketch, etc.).
- **Per-board configuration** via custom post types or dedicated entities.
- **Divi and Elementor widgets/modules** for drop-in usage.
- **Import/export** of menu presets.
- **Accessibility and localization** improvements.

---

## Development

- Minimum WordPress version: 5.8+
- Recommended PHP version: 7.4+

### Local environment

1. Ensure you have a working WordPress installation.
2. Place this plugin in the `wp-content/plugins` directory.
3. Activate the plugin from the WordPress admin.

### Coding standards

This project aims to follow:

- WordPress PHP coding standards.
- Escaping and sanitization best practices for all output and input.

Pull requests and issues are welcome.

---

## License

This plugin is licensed under the **GPL-2.0-or-later** license.

---

## Credits

Chalkboard Menu Pro is developed and maintained by **Ez IT Solutions**.

Inspired by classic framed chalkboard menus used in cafes and coffee shops.
