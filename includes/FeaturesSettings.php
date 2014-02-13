<?php
/**
 * Plugin Name: Hackerspace
 */

class Settings_Features
{

    public function __construct()
    {
        // TODO create default values for options
        $this->options = get_option('hackerspace_features');
    }

    /**
     * Whitelist the features settings
     */
    public function register_settings()
    {
        register_setting('hackerspace_features', 'hackerspace_features', array($this, 'settings_validate'));
    }

    /**
     * Validate the plugin features settings
     *
     * @return string
     */
    public function settings_validate($input)
    {
        // input options are in an array, we use a stdClass object
        $output = json_decode(json_encode($input));
        // sanitization
        // TODO add validation

        return $output;
    }

    public function help_tab()
    {
        $help_tab = new stdClass;
        $help_tab->id = 'wp-hackerspace-features';
        $help_tab->title = __('Features', 'wp-hackerspace');
        $help_tab->content = '<p>Features help text</p>';

        return $help_tab;
    }

    // render the main section description text
    //public function main_section()
    //{
    //}

}
