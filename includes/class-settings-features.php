<?php

/**
 * Render the setting form for the plugin features
 *
 * @since 0.3
 */
class Settings_Features
{
    /** Constructor for the Settings_Features class */
    public function __construct()
    {
        // TODO create default values for options
        $this->options = get_option('hackerspace_features');
    }

    /** Whitelist the features settings */
    public function register_settings()
    {
        register_setting('hackerspace_features', 'hackerspace_features', array($this, 'settings_validate'));
    }

    /**
     * Validate the plugin features settings
     *
     * @param array $input Inputed values from the settings form
     *
     * @return stdClass object
     */
    public function settings_validate($input)
    {
        // convert inputed array options to a stdClass object
        $output = json_decode(json_encode($input));
        // sanitization
        // TODO add validation

        return $output;
    }

    /**
     * Generate an object to display on top help tab
     *
     * @return stdClass object
     */
    public function help_tab()
    {
        $help_tab = new stdClass;
        $help_tab->id = 'wp-hackerspace-features';
        $help_tab->title = __('Features', 'wp-hackerspace');
        $help_tab->content = '<p>Features help text</p>';

        return $help_tab;
    }

    /** Render the main section description text */
    public function main_section()
    {
        //TODO add description text
    }

}
