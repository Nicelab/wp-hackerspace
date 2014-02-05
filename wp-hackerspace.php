<?php
/**
 * Plugin Name: Hackerspace
 * Plugin URI: https://github.com/nicelab/wp-hackerspace
 * Author: Nicelab.org
 * Author URI: http://nicelab.org/
 * Description: Add custom post types useful for hackerspaces and expose informations trough the Space API.
 * Version: 0.2
 * Copyright: (c) 2014 Nicelab.org
 * License: Expat/MIT License
 * Text Domain: wp-hackerspace
 * Domain Path: /languages
 */


// main class for the plugin
class WPHackerspace
{
    // class contructor
    public function __construct()
    {
        // load translations
        load_plugin_textdomain('wp-hackerspace', false, plugin_dir_path(__FILE__).'/languages');

        // configure the plugin settings
        add_action('admin_init', array($this, 'admin_init'));

        // enable the admin setting menu
        add_action('admin_menu', array($this, 'admin_menu'));

        // enable the Space Api json feed
        add_action('init', array($this, 'spaceapi_feed'));

        // enable the spaceapi rel element in the blog headers
        add_action('wp_head', array($this, 'spaceapi_rel'));

        // enable a settings link in the WordPress plugins menu
        add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'plugin_action_links'));

    }

    // activate the plugin
    public static function activate()
    {
    }

    // deactivate the plugin
    public static function deactivate()
    {
    }

    // uninstall the plugin
    public static function uninstall()
    {
    }

    // register the plugin settings
    public function admin_init()
    {
        // TODO tests between serialisation between object, array and JSON
        // TODO move most of this in the SpaceApi class (at last fields, and maybe sections registration)
        include_once(plugin_dir_path(__FILE__).'includes/Settings.php'); //TODO use autoloader
        $Settings = new Settings();
        //register_setting('wp-hackerspace', 'hackerspace_settings', array($Settings, 'hackerspace_settings_validate'));
        register_setting('wp-hackerspace', 'spaceapi_settings', array($Settings, 'spaceapi_settings_validate'));

        add_settings_section('main_section', __('Main informations', 'wp-hackerspace'), array($Settings, 'spaceapi_main_section'), 'spaceapi_settings');
        add_settings_section('location_section', __('Location', 'wp-hackerspace'), array($Settings, 'spaceapi_location_section'), 'spaceapi_settings');
        add_settings_section('contact_section', __('Contact', 'wp-hackerspace'), array($Settings, 'spaceapi_contact_section'), 'spaceapi_settings');

        add_settings_field('spaceapi_space', __('Space name', 'wp-hackerspace'), array($Settings, 'spaceapi_space_field'), 'spaceapi_settings', 'main_section');
        add_settings_field('spaceapi_url', __('Space url', 'wp-hackerspace'), array($Settings, 'spaceapi_url_field'), 'spaceapi_settings', 'main_section');
        add_settings_field('spaceapi_logo', __('Logo url', 'wp-hackerspace'), array($Settings, 'spaceapi_logo_field'), 'spaceapi_settings', 'main_section');
        add_settings_field('spaceapi_address', __('Address', 'wp-hackerspace'), array($Settings, 'spaceapi_address_field'), 'spaceapi_settings', 'location_section');
        add_settings_field('spaceapi_lat', __('Latitude', 'wp-hackerspace'), array($Settings, 'spaceapi_lat_field'), 'spaceapi_settings', 'location_section');
        add_settings_field('spaceapi_lon', __('Longitude', 'wp-hackerspace'), array($Settings, 'spaceapi_lon_field'), 'spaceapi_settings', 'location_section');
        add_settings_field('spaceapi_email', __('Email', 'wp-hackerspace'), array($Settings, 'spaceapi_email_field'), 'spaceapi_settings', 'contact_section');
        add_settings_field('spaceapi_phone', __('Phone', 'wp-hackerspace'), array($Settings, 'spaceapi_phone_field'), 'spaceapi_settings', 'contact_section');
        add_settings_field('spaceapi_sip', __('SIP', 'wp-hackerspace'), array($Settings, 'spaceapi_sip_field'), 'spaceapi_settings', 'contact_section');
        add_settings_field('spaceapi_irc', __('IRC', 'wp-hackerspace'), array($Settings, 'spaceapi_irc_field'), 'spaceapi_settings', 'contact_section');
        add_settings_field('spaceapi_twitter', __('Twitter', 'wp-hackerspace'), array($Settings, 'spaceapi_twitter_field'), 'spaceapi_settings', 'contact_section');
        add_settings_field('spaceapi_facebook', __('Facebook', 'wp-hackerspace'), array($Settings, 'spaceapi_facebook_field'), 'spaceapi_settings', 'contact_section');
        add_settings_field('spaceapi_identica', __('Identica', 'wp-hackerspace'), array($Settings, 'spaceapi_identica_field'), 'spaceapi_settings', 'contact_section');
        add_settings_field('spaceapi_foursquare', __('Foursquare', 'wp-hackerspace'), array($Settings, 'spaceapi_foursquare_field'), 'spaceapi_settings', 'contact_section');
        add_settings_field('spaceapi_ml', __('Mailling list', 'wp-hackerspace'), array($Settings, 'spaceapi_ml_field'), 'spaceapi_settings', 'contact_section');
        add_settings_field('spaceapi_jabber', __('Jabber', 'wp-hackerspace'), array($Settings, 'spaceapi_jabber_field'), 'spaceapi_settings', 'contact_section');
    }

    // configure the plugin settings menu
    public function admin_menu()
    {
        add_options_page(
            __('Hackerspace', 'wp-hackerspace'),
            __('Hackerspace', 'wp-hackerspace'),
            'manage_options',
            'hackerspace_options',
            array($this, 'plugin_settings_template')
        );
    }

    // TODO move this in Settings class ?
    // render the settings template
    public function plugin_settings_template()
    {
        if (! current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-hackerspace'));
        }
        include(sprintf(plugin_dir_path(__FILE__).'templates/settings.php'));
    }

    // render the settings link in the in the WordPress plugins menu
    public function plugin_action_links($links)
    {
        $links[] = '<a href="'.get_admin_url(null, 'options-general.php?page=hackerspace_options').'">'.__('Settings', 'wp-hackerspace').'</a>';
        return $links;
    }

    // render the Space Api json feed
    public function spaceapi_feed()
    {
        include_once(plugin_dir_path(__FILE__).'includes/SpaceApi.php'); //TODO use autoloader
        $SpaceApi = new SpaceApi();
        add_feed('spaceapi', array($SpaceApi, 'spaceapi_json'));
    }

    // add the spaceapi rel element to the blog headers
    public function spaceapi_rel()
    {
        echo '<link rel="space-api" href="'.get_bloginfo('url').'?feed=spaceapi" />'."\n";
    }

}


// register activation, deactivation and uninstall hooks
register_activation_hook(__FILE__, array('WPHackerspace', 'activate'));
register_deactivation_hook(__FILE__, array('WPHackerspace', 'deactivate'));
register_uninstall_hook(__FILE__, array('WPHackerspace', 'uninstall'));

// instantiate the plugin class
$wpHackerspace = new WPHackerspace();
