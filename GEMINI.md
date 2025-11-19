# Project Overview

This project is a single-page web application that displays a customized Google Map with custom markers for specific locations. When a marker is clicked, an info window with details about the location appears.

**Technologies:**

*   HTML
*   JavaScript
*   Tailwind CSS
*   Google Maps JavaScript API

# Building and Running

This is a WordPress plugin. There is no build process required for the plugin itself.

1.  **WordPress Installation:** Install the plugin in your WordPress site.
2.  **API Key Configuration:** Configure your Google Maps API Key in the WordPress admin panel under Settings -> Google Maps Key.

# Development Conventions

*   The JavaScript code was previously embedded in the HTML file. For better organization, it is recommended to move the JavaScript code to a separate .js file. (Completed: JavaScript is in js/map.js)
*   The code uses ar for variable declarations. It is recommended to use let and const instead.
*   The Google Maps API key is hardcoded in the HTML file. For better security, it is recommended to use a more secure method to store and load the API key, such as environment variables or a separate configuration file.

# Shortcode Usage

To display the default map, simply use the following shortcode in your WordPress editor:

[google_map]

You can also specify a map mode using the mode attribute:

[google_map mode="default"] - Displays the default map with predefined locations.
[google_map mode="geolocation"] - (Assuming this mode is implemented) Displays a map centered on the user's current geolocation.

# Assets

The 401gold-map-marker.svg icon has been moved to the google-maps-plugin-dist/assets/ directory.
