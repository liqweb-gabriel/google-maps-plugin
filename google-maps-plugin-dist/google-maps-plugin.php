<?php
/**
 * Plugin Name: Google Maps Shortcode
 * Description: Adds a shortcode to display a Google Map
 * Version: 1.0
 * Author: Gemini
 */

// 1. Add a menu item to the admin panel for API key settings
function ozy_google_maps_add_admin_menu() {
    add_options_page(
        'Google Maps API Key',
        'Google Maps Key',
        'manage_options',
        'google_maps_api_key',
        'ozy_google_maps_options_page'
    );
}
add_action('admin_menu', 'ozy_google_maps_add_admin_menu');

// 2. Create the settings page content
function ozy_google_maps_options_page() {
    ?>
    <div class="wrap">
        <h2>Google Maps API Key Settings</h2>
        <form action="options.php" method="post">
            <?php
            settings_fields('ozy_google_maps_options');
            do_settings_sections('google_maps_api_key');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// 3. Register the setting and the field
function ozy_google_maps_settings_init() {
    register_setting('ozy_google_maps_options', 'ozy_google_maps_api_key');

    add_settings_section(
        'ozy_google_maps_section',
        'API Key',
        'ozy_google_maps_section_callback',
        'google_maps_api_key'
    );

    add_settings_field(
        'ozy_google_maps_api_key_field',
        'Google Maps API Key',
        'ozy_google_maps_api_key_field_render',
        'google_maps_api_key',
        'ozy_google_maps_section'
    );
}
add_action('admin_init', 'ozy_google_maps_settings_init');

function ozy_google_maps_section_callback() {
    echo 'Enter your Google Maps API Key below. This key will be used for all maps displayed via the shortcode.';
}

function ozy_google_maps_api_key_field_render() {
    $api_key = get_option('ozy_google_maps_api_key');
    ?>
    <input type="text" name="ozy_google_maps_api_key" value="<?php echo esc_attr($api_key); ?>" style="width: 400px;">
    <?php
}

// 4. The Shortcode Logic
function ozy_google_map_shortcode($atts) {
    // Set default attributes
    $atts = shortcode_atts(
        array(
            'mode' => 'default', // 'default' or 'geolocation'
        ),
        $atts,
        'google_map'
    );

    // Get the saved API key
    $api_key = get_option('ozy_google_maps_api_key');
    if (!$api_key) {
        return '<p style="color: red;">Error: Google Maps API key is not set. Please set it in the admin panel under Settings -> Google Maps Key.</p>';
    }

    // Enqueue the scripts and styles
    wp_enqueue_style('ozy-google-maps-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_enqueue_script('ozy-google-maps-script', plugin_dir_url(__FILE__) . 'js/map.js', array(), '1.0', true);
    wp_enqueue_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . esc_attr($api_key) . '&callback=initMap', array('ozy-google-maps-script'), null, true);

    // Pass data from PHP to JavaScript
    $data_to_pass = array(
        'mode' => $atts['mode'],
        'locations' => array(
            array(
                'position' => array('lat' => 43.7183585, 'lng' => -79.4558835),
                'title' => '401Gold Dufferin',
                'icon_mobile' => plugin_dir_url(__FILE__) . 'assets/401gold-map-marker.svg',
                'icon_desktop' => plugin_dir_url(__FILE__) . 'assets/401gold-map-marker.svg',
                'address' => '3200 Dufferin St Unit 19B, North York, ON M6A 3B2'
            ),
            array(
                'position' => array('lat' => 43.7788456, 'lng' => -79.3081644),
                'title' => '401Gold Warden',
                'icon_mobile' => plugin_dir_url(__FILE__) . 'assets/401gold-map-marker.svg',
                'icon_desktop' => plugin_dir_url(__FILE__) . 'assets/401gold-map-marker.svg',
                'address' => '2190 Warden Ave. Unit G5, Toronto, ON M1T 1V6'
            )
        )
    );
    wp_localize_script('ozy-google-maps-script', 'map_data', $data_to_pass);

    // Return the HTML for the map container
    return '<div id="map"></div>';
}
add_shortcode('google_map', 'ozy_google_map_shortcode');

?>