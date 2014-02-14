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


// include the required external classes files
require_once(plugin_dir_path(__FILE__).'includes/class-post-type-project.php');
require_once(plugin_dir_path(__FILE__).'includes/class-settings-features.php');
require_once(plugin_dir_path(__FILE__).'includes/class-settings-space-api.php');
require_once(plugin_dir_path(__FILE__).'includes/class-space-api.php');


/**
 * Main class for the plugin
 *
 * @since 0.1
 */
class Hackerspace
{
    /**
     * Constructor for the Hackerspace class
     *
     * Register Wordpress plugins hooks
     */
    public function __construct()
    {
        // register activation, deactivation and uninstall hooks for the plugin
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        register_uninstall_hook(__FILE__, array('Hackerspace', 'uninstall'));

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
        add_action('init', array('Post_Type_Project', 'register_project_post_type'));

        // enable a settings link in the WordPress plugins menu
        add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'plugin_action_links'));

        // Temporary debug lines until an update mecanism if added. Uncomment to reset default values or missing ones after upgrade
        //$Space_Api = new Space_Api();
        //update_option('hackerspace_spaceapi', $Space_Api->set_default_spaceapi());

        // instantiate the required external classes
        $this->Post_Type_Project = new Post_Type_Project();
        $this->Settings_Features = new Settings_Features();
        $this->Settings_Space_Api = new Settings_Space_Api();
        $this->Space_Api = new Space_Api();
    }

    /** Activate the plugin */
    public static function activate()
    {
        // flush rewrite rules for custom post types permalinks
        flush_rewrite_rules();
    }

    /** Deactivate the plugin */
    public static function deactivate()
    {
    }

    /** Uninstall the plugin */
    public static function uninstall()
    {
        delete_option('hackerspace_features');  //TODO test this
        delete_option('hackerspace_spaceapi');
    }

    /** Register the plugin settings */
    public function admin_init()
    {
        $this->Settings_Features->register_settings();
        $this->Settings_Space_Api->register_settings();
    }

    /** Configure the plugin settings menu */
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

    /** Render the settings template */
    public function plugin_settings_template()
    {
        if (! current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'wp-hackerspace'));
        }
        include(sprintf(plugin_dir_path(__FILE__).'templates/settings.php'));
    }

    /** Render the contextual help drop-down menu
     *
     * @param object    $contextual_help Actual contaxtual help
     * @param text      $screen_id       ID of the WordPress screen
     * @param WP_Screen $screen          WordPress $screen global
     *
     * @return object Modified contextual help
     */
    public function plugin_contextual_help($contextual_help, $screen_id, $screen)
    {
        $features_help_tab = $this->Settings_Features->help_tab();
        $spaceapi_help_tab = $this->Settings_Space_Api->help_tab();
        $projects_help_tab = $this->Post_Type_Project->help_tab();

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

        if ($screen_id == 'hackerspace_project' || $screen_id == 'edit-hackerspace_project') {
            $screen->add_help_tab(array(
                'id'        => $projects_help_tab->id,
                'title'     => $projects_help_tab->title,
                'content'   => $projects_help_tab->content,
            ));
        }

        return $contextual_help;
    }

    /**
     * Render the settings link in the in the WordPress plugins menu
     *
     * @param array $links Array of links displayed in the WordPress plugins menu
     *
     * @return array
     */
    public function plugin_action_links($links)
    {
        $links[] = '<a href="'.get_admin_url(null, 'options-general.php?page=hackerspace_options').'">'.__('Settings', 'wp-hackerspace').'</a>';

        return $links;
    }

    /** Render the Space Api json feed */
    public function spaceapi_feed()
    {
        add_feed('spaceapi', array($this->Space_Api, 'spaceapi_json'));
    }

    /** Add the spaceapi rel element to the blog headers */
    public function spaceapi_rel()
    {
        echo '<link rel="space-api" href="'.get_bloginfo('url').'?feed=spaceapi" />'."\n";
    }

}


// instantiate the plugin class
$Hackerspace = new Hackerspace();
