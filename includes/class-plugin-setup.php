<?php

/**
 * Activate, update and deactivate the plugin
 *
 * @since 0.3
 */
class Plugin_Setup
{

    /** Activate the plugin */
    public function activate()
    {
        // check if the user have the right to do this
        if (! current_user_can('activate_plugins')) {
            return;
        }
        // enable the 'hacker' role
        self::add_hacker_role();
        // set capabilities
        self::set_capabilities();
        // flush rewrite rules for custom post types permalinks
        flush_rewrite_rules();
    }

    /** Deactivate the plugin */
    public function deactivate()
    {
        // check if the user have the right to do this
        if (! current_user_can('activate_plugins')) {
            return;
        }
        // remove the 'hacker' role
        self::remove_hacker_role();
        // remove capabilities
        self::remove_capabilities();
        // flush rewrite rules for custom post types permalinks
        flush_rewrite_rules();
    }

    /**
     * Get the plugin version number from 'hackerspace_version' options
     *
     * @return text $plugin_version version number from the options
     */
    private function plugin_version()
    {
        // if no version exist, we assume then it's first install an set the current version
        if (false == get_option('hackerspace_version')) {
            add_option('hackerspace_version', Hackerspace::PLUGIN_VERSION);
        }
        // get the version number
        $plugin_version = get_option('hackerspace_version');

        return $plugin_version;
    }

    /** Update the plugin */
    public function update()
    {
        $plugin_version = self::plugin_version();
        switch($plugin_version) {
            case '0.3':
                //placeholder for futures updates
                //update_option('hackerspace_version', Hackerspace::PLUGIN_VERSION);
        }
    }

    /**
     * Add and 'hacker' role with 'contributor' role capabilities
     *
     * This role can
     * - create blog post but not publish them
     * - create, edit, publish and delete his own custom post types (see in class-post-type files)
     * - read the privates custom post types
     * This role cannot
     * - manage the settings of the plugin
     * - edit and delete the custom post type from others
     */
    private function add_hacker_role()
    {
        $contributor = get_role('contributor');
        if (!get_role('hacker')) {
            add_role(
                'hacker',
                __('Hacker', 'wp-hackerspace'),
                $contributor->capabilities
            );
        }
    }

    /** remove the 'hacker' role */
    private function remove_hacker_role()
    {
        remove_role('hacker');
    }

    /** Set capabilities on custom post type */
    private function set_capabilities()
    {
        // limited cababilities for 'hacker' role
        // full capabilities for 'adminstrator' and 'editor' roles
        Post_Type_Project::set_capabilities('administrator');
        Post_Type_Project::set_capabilities('editor');
        Post_Type_Project::set_capabilities('hacker');
    }

    /** Remove capabilities on custom post type */
    private function remove_capabilities()
    {
        // remove capabilities for 'adminstrator' and 'editor' roles
        Post_Type_Project::remove_capabilities('administrator');
        Post_Type_Project::remove_capabilities('editor');
    }

}

