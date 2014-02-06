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

        // enable the contextual help
        add_action('contextual_help', array($this, 'plugin_contextual_help'), 10, 3);

        // enable a settings link in the WordPress plugins menu
        add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'plugin_action_links'));


        // TODO move all this stuff in a better place (activate ?) (class constructors?)
        if (false==get_option('hackerspace_plugin_features')) {
            add_option('hackerspace_plugin_features');
        }

        //create the option object
        $default_options = new stdClass;
        $default_options->space = null;
        $default_options->logo = null;
        $default_options->url = null;
        $default_options->location->address = null;
        $default_options->location->lat = null;
        $default_options->location->lon = null;
        $default_options->contact->email = null;
        $default_options->contact->phone = null;
        $default_options->contact->sip = null;
        $default_options->contact->irc = null;
        $default_options->contact->twitter = null;
        $default_options->contact->facebook = null;
        $default_options->contact->identica = null;
        $default_options->contact->foursquare = null;
        $default_options->contact->ml = null;
        $default_options->contact->jabber = null;

        //if (false==get_option('hackerspace_plugin_spaceapi')) {
            //add_option('hackerspace_plugin_spaceapi', $spaceapiarray);
        //}
        //update_option('hackerspace_plugin_spaceapi', $spaceapiarray);

        if (false==get_option('hackerspace_spaceapi')) {
            add_option('hackerspace_spaceapi', $default_options);
        }
        //update_option('hackerspace_spaceapi', $default_options);
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
        //delete_option('hackerspace_plugin_spaceapi'); //TODO test this
    }

    // register the plugin settings
    public function admin_init()
    {
        // TODO tests between serialisation between object, array and JSON
        // TODO move most of this in the SpaceApi class (at last fields, and maybe sections registration)
        include_once(plugin_dir_path(__FILE__).'includes/Settings.php'); //TODO use autoloader
        $Settings = new Settings(); //TODO create one class for space api settings and one for features ?
        //register_setting('hackerspace_features', 'hackerspace_features', array($Settings, 'hackerspace_features_validate'));
        register_setting('hackerspace_spaceapi', 'hackerspace_spaceapi', array($Settings, 'spaceapi_settings_validate'));

        add_settings_section('main_section', __('Main informations', 'wp-hackerspace'), array($Settings, 'spaceapi_main_section'), 'hackerspace_spaceapi');
        add_settings_section('location_section', __('Location', 'wp-hackerspace'), array($Settings, 'spaceapi_location_section'), 'hackerspace_spaceapi');
        add_settings_section('contact_section', __('Contact', 'wp-hackerspace'), array($Settings, 'spaceapi_contact_section'), 'hackerspace_spaceapi');

        add_settings_field('space', __('Space name', 'wp-hackerspace'), array($Settings, 'spaceapi_space_field'), 'hackerspace_spaceapi', 'main_section');
        add_settings_field('url', __('Space url', 'wp-hackerspace'), array($Settings, 'spaceapi_url_field'), 'hackerspace_spaceapi', 'main_section');
        add_settings_field('logo', __('Logo url', 'wp-hackerspace'), array($Settings, 'spaceapi_logo_field'), 'hackerspace_spaceapi', 'main_section');
        add_settings_field('address', __('Address', 'wp-hackerspace'), array($Settings, 'spaceapi_address_field'), 'hackerspace_spaceapi', 'location_section');
        add_settings_field('lat', __('Latitude', 'wp-hackerspace'), array($Settings, 'spaceapi_lat_field'), 'hackerspace_spaceapi', 'location_section');
        add_settings_field('lon', __('Longitude', 'wp-hackerspace'), array($Settings, 'spaceapi_lon_field'), 'hackerspace_spaceapi', 'location_section');
        add_settings_field('email', __('Email', 'wp-hackerspace'), array($Settings, 'spaceapi_email_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('phone', __('Phone', 'wp-hackerspace'), array($Settings, 'spaceapi_phone_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('sip', __('SIP', 'wp-hackerspace'), array($Settings, 'spaceapi_sip_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('irc', __('IRC', 'wp-hackerspace'), array($Settings, 'spaceapi_irc_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('twitter', __('Twitter', 'wp-hackerspace'), array($Settings, 'spaceapi_twitter_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('facebook', __('Facebook', 'wp-hackerspace'), array($Settings, 'spaceapi_facebook_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('identica', __('Identica', 'wp-hackerspace'), array($Settings, 'spaceapi_identica_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('foursquare', __('Foursquare', 'wp-hackerspace'), array($Settings, 'spaceapi_foursquare_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('ml', __('Mailling list', 'wp-hackerspace'), array($Settings, 'spaceapi_ml_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('jabber', __('Jabber', 'wp-hackerspace'), array($Settings, 'spaceapi_jabber_field'), 'hackerspace_spaceapi', 'contact_section');
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

    // render the contextual help drop-down menu
    public function plugin_contextual_help($contextual_help, $screen_id, $screen)
    {
        if ($screen_id=='settings_page_hackerspace_options') {
            $screen->add_help_tab(array(
                'id'        => 'wp-hackerspace-overview',
                'title'     => __('Overview', 'wp-hackerspace'),
                'content'   => '<p>Overview help text</p>'
            ));
            $screen->add_help_tab(array(
                'id'        => 'wp-hackerspace-spaceapi',
                'title'     => __('Space Api', 'wp-hackerspace'),
                'content'   => '<p>Space Api help text</p>'
            ));
            // help sidebar links
            $screen->set_help_sidebar('<p>Sidebar help text</p>');
        }
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
