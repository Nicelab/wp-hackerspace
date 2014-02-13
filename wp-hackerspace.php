<?php
/**
 * Plugin Name: Hackerspace
 * Plugin URI: https://github.com/nicelab/wp-hackerspace
 * Author: Nicelab.org
 * Author URI: http://nicelab.org/
 * Description: Add custom post types useful for hackerspaces and expose informations trough the Space API.
 * Version: 0.3
 * Copyright: (c) 2014 Nicelab.org
 * License: Expat/MIT License
 * Text Domain: wp-hackerspace
 * Domain Path: /languages
 */

// TODO add an update mecanism


/* include required classes */
require_once(plugin_dir_path(__FILE__).'includes/ProjectPostType.php');
require_once(plugin_dir_path(__FILE__).'includes/FeaturesSettings.php');
require_once(plugin_dir_path(__FILE__).'includes/SpaceApiSettings.php');
require_once(plugin_dir_path(__FILE__).'includes/SpaceApi.php');


/**
 * Main class for the plugin
 */
class WPHackerspace
{

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

        // enable the Project post type
        //include_once(plugin_dir_path(__FILE__).'includes/ProjectPostType.php');
        $ProjectPostType = new ProjectPostType;
        add_action('init', array($ProjectPostType, 'register_project_post_type'));

        // enable a settings link in the WordPress plugins menu
        add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'plugin_action_links'));

        // Temporary debug lines until an update mecanism if added. Uncomment to reset default values or missing ones after upgrade
        //$SpaceApi = new SpaceApi();
        //update_option('hackerspace_spaceapi', $SpaceApi->set_default_spaceapi());
    }

    /**
     * Activate the plugin
     */
    public static function activate()
    {
    }

    /**
     * Deactivate the plugin
     */
    public static function deactivate()
    {
    }

    /**
     * Uninstall the plugin
     */
    public static function uninstall()
    {
        delete_option('hackerspace_features');  //TODO test this
        delete_option('hackerspace_spaceapi');
    }

    /**
     * Register the plugin settings
     */
    public function admin_init()
    {
        // TODO tests between serialisation between object, array and JSON
        $FeaturesSettings = new FeaturesSettings();
        $SpaceApiSettings = new SpaceApiSettings(); //TODO create one class for space api settings and one for features ?
        //register_setting('hackerspace_features', 'hackerspace_features', array($Settings, 'hackerspace_features_validate'));

        $FeaturesSettings->register_settings();
        $SpaceApiSettings->register_settings();
    }

    /**
     * Configure the plugin settings menu
     */
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

    /**
     * Render the settings template
     */
    public function plugin_settings_template()
    {
        if (! current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-hackerspace'));
        }
        include(sprintf(plugin_dir_path(__FILE__).'templates/settings.php'));
    }

    /**
     * Render the contextual help drop-down menu
     */
    public function plugin_contextual_help($contextual_help, $screen_id, $screen)
    {
        $FeaturesSettings = new FeaturesSettings();
        $features_help_tab = $FeaturesSettings->help_tab();
        $SpaceApiSettings = new SpaceApiSettings();
        $spaceapi_help_tab = $SpaceApiSettings->help_tab();

        if ($screen_id == 'settings_page_hackerspace_options') {
            $screen->add_help_tab(array(
                'id'        => 'wp-hackerspace-overview',
                'title'     => __('Overview', 'wp-hackerspace'),
                'content'   => '<p>Overview help text</p>',
            ));
            $screen->add_help_tab(array(
                'id'        => $features_help_tab->id,
                'title'     => $features_help_tab->title,
                'content'   => $features_help_tab->content,
            ));
            $screen->add_help_tab(array(
                'id'        => $spaceapi_help_tab->id,
                'title'     => $spaceapi_help_tab->title,
                'content'   => $spaceapi_help_tab->content,
            ));
            // help sidebar links
            $screen->set_help_sidebar('<p><strong>'.__('For more information:', 'wp-hackerspace').'</strong></p>');
        }
    }

    /**
     * Render the settings link in the in the WordPress plugins menu
     *
     * @return object
     */
    public function plugin_action_links($links)
    {
        $links[] = '<a href="'.get_admin_url(null, 'options-general.php?page=hackerspace_options').'">'.__('Settings', 'wp-hackerspace').'</a>';

        return $links;
    }

    /**
     * Render the Space Api json feed
     */
    public function spaceapi_feed()
    {
        $SpaceApi = new SpaceApi();
        add_feed('spaceapi', array($SpaceApi, 'spaceapi_json'));
    }

    /**
     *  Add the spaceapi rel element to the blog headers
     */
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
