<?php
/**
 * Plugin Name: Hackerspace
 */


class Settings
{
    // validate the Space api settings
    public function spaceapi_settings_validate()
    {
    }


    // render the settings page
    public function settings_page()
    {
    }

    // render the Space Api section description text
    public function spaceapi_settings_section()
    {
        _e('Informations about your space avalaible with the Space Api.', 'wp-hackerspace');
    }

    // render the Space Api form fields
    // TODO we may create a generic function for this, or search if there is a WordPress official one
    public function spaceapi_space_field()
    {
        //$options = get_option('spaceapi_settings');
        $option = 'space';
        $description = __('The name of your space.', 'wp-hackerspace');
        echo "<input name='fake_space' id='fake_space' value='{$option}' class='regular-text' type='text' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_url_field()
    {
        $option = 'url';
        $description = __('URL to your space website.', 'wp-hackerspace');
        echo "<input name='fake_url' id='fake_url' value='{$option}' class='regular-text' type='text' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_logo_field()
    {
        $option = 'logo';
        $description = __('URL to your space logo.', 'wp-hackerspace');
        echo "<input name='fake_logo' id='fake_logo' value='{$option}' class='regular-text' type='text' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

}
