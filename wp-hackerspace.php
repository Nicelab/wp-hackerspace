<?php
/**
 * Plugin Name: Hackerspace
 * Plugin URI: https://github.com/nicelab/wp-hackerspace
 * Author: Nicelab.org
 * Author URI: http://nicelab.org/
 * Description: Add custom post types useful for hackerspaces and expose informations trough the Space API.
 * Version: 0.1
 * Copyright: (c) 2014 Nicelab.org
 * License: Expat/MIT License
 * Text Domain: wp-hackerspace
 * Domain Path: /languages
 */


class WPHackerspace
{
    // class contructor
    public function __construct()
    {
        // load translations
        load_plugin_textdomain('wp-hackerspace', false, plugin_dir_path(__FILE__).'/languages');

        //
        add_action('admin_init', array($this, 'admin_init'));

        // enable the admin setting menu
        add_action('admin_menu', array($this, 'admin_menu'));
    }

    // activate the plugin
    public static function activate()
    {
    }

    // deactivate the plugin
    public static function deactivate()
    {
    }

    // register the plugin settings
    public function admin_init()
    {
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

    // render the settings template
    public function plugin_settings_template()
    {
        if (! current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-hackerspace'));
        }
        include(sprintf(plugin_dir_path(__FILE__).'templates/settings.php'));
    }

}


// register activation and deactivation hooks
register_activation_hook(__FILE__, array('WPHackerspace', 'activate'));
register_deactivation_hook(__FILE__, array('WPHackerspace', 'deactivate'));

// instantiate the plugin class
$wpHackerspace = new WPHackerspace();
