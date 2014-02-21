<?php

/**
 * Activate, update and deactivate the plugin
 *
 * @since 0.3
 */
class Plugin_Setup
{

//    /** Constructor for the Plugin_Update class */
//    public function __construct()
//    {
//        // check if hackerspace_version is in the database
//        if (true == get_option('hackerspace_version')) {
//            $ version =
//            add_option('hackerspace_features', $this->set_default_features());
//        }
//    }

    /** Activate the plugin */
    public function activate()
    {
        // check if the user have the right to do this
        if (! current_user_can('activate_plugins')) {
            return;
        }
        // enable the 'hacker' role
        //require_once(plugin_dir_path(__FILE__).'includes/class-role-hacker.php');
        //$Role_Hacker = new Role_Hacker;
        //$Role_Hacker->add_hacker_role();

        // flush rewrite rules for custom post types permalinks
        flush_rewrite_rules();
    }

    /** Deactivate the plugin */
    public static function deactivate()
    {
        // check if the user have the right to do this
        if (! current_user_can('activate_plugins')) {
            return;
        }
        // flush rewrite rules for custom post types permalinks
        flush_rewrite_rules();
    }

    /** Update the plugin */
    public function update()
    {
    }



}
