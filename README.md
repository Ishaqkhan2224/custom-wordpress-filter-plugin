# custom-wordpress-filter-plugin

A lightweight WordPress plugin that allows users to filter posts or custom post types by taxonomy and custom fields using AJAX (initial load only), with front-end rendering handled using Vue.js. Designed for speed, flexibility, and easy integration with shortcodes or custom templates.

## Usage

To display the filtering system, simply use the shortcode:

`[mik_clinicians_filter]`

Place it on any WordPress page. Users will see filter fields and a list of clinician posts with instant client-side filtering.

## How It Works

- On page load, the plugin sends a single AJAX request to fetch all "clinician" posts along with their assigned data: title, description, image, and categories.
- The response is returned in JSON format and rendered on the DOM using **Vue.js** (included via CDN).
- Once the data is loaded, all filtering actions are handled instantly on the client side with **no additional AJAX requests** or page reloads.

## Features

- Built with Vue.js (via CDN)
- Filter by taxonomy/category
- Fast front-end filtering (after initial AJAX)
- Responsive layout with shortcode integration
- Optimized for performance and smooth UX
