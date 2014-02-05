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
//    public function settings_page()
//    {
//    }

    // render the Space Api section description text
    public function spaceapi_main_section()
    {
        _e('Main informations about your space.', 'wp-hackerspace');
    }

    public function spaceapi_location_section()
    {
        _e('Position data such as a postal address or geographic coordinates.', 'wp-hackerspace');
    }

    public function spaceapi_contact_section()
    {
        _e('Contact information about your space. You must define at least one.', 'wp-hackerspace');
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
        echo "<input name='fake_url' id='fake_url' value='{$option}' class='regular-text code' type='url' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_logo_field()
    {
        $option = 'logo';
        $description = __('URL to your space logo.', 'wp-hackerspace');
        echo "<input name='fake_logo' id='fake_logo' value='{$option}' class='regular-text code' type='url' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_address_field()
    {
        $option = 'address';
        $description = __('The postal address of your space. Example: Netzladen e.V., Breite Straße 74, 53111 Bonn, Germany', 'wp-hackerspace');
        echo "<input name='fake_address' id='fake_address' value='{$option}' class='regular-text' type='text' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    // TODO test coma/point, add step and max min
    public function spaceapi_lat_field()
    {
        $option = 'lat';
        $description = __('Latitude of your space location, in degree with decimal places. Use positive values for locations north of the equator, negative values for locations south of equator.', 'wp-hackerspace');
        echo "<input name='fake_lat' id='fake_lat' value='{$option}' class='small-text' type='number' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_lon_field()
    {
        $option = 'lon';
        $description = __('Longitude of your space location, in degree with decimal places. Use positive values for locations west of Greenwich, and negative values for locations east of Greenwich.', 'wp-hackerspace');
        echo "<input name='fake_lon' id='fake_lon' value='{$option}' class='small-text' type='number' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_email_field()
    {
        $option = 'email';
        $description = __('E-mail address for contacting your space.', 'wp-hackerspace');
        echo "<input name='fake_email' id='fake_email' value='{$option}' class='regular-text ltr' type='email' />";
        echo "<p class='description'>{$description}</p>";
    }

    // TODO test html5 phone validation
    public function spaceapi_phone_field()
    {
        $option = 'phone';
        $description = __('Phone number, including country code with a leading plus sign. Example: +1 800 555 4567', 'wp-hackerspace');
        echo "<input name='fake_phone' id='fake_phone' value='{$option}' class='regular-text' type='tel' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_sip_field()
    {
        $option = 'sip';
        $description = __('URI for Voice-over-IP via SIP. Example: sip:yourspace@sip.example.org', 'wp-hackerspace');
        echo "<input name='fake_sip' id='fake_sip' value='{$option}' class='regular-text code' type='url' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_irc_field()
    {
        $option = 'irc';
        $description = __('URL of the IRC channel, in the form irc://example.org/#channelname', 'wp-hackerspace');
        echo "<input name='fake_irc' id='fake_irc' value='{$option}' class='regular-text code' type='url' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_twitter_field()
    {
        $option = 'twitter';
        $description = __('Twitter handle, with leading @', 'wp-hackerspace');
        echo "<input name='fake_twitter' id='fake_twitter' value='{$option}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_facebook_field()
    {
        $option = 'facebook';
        $description = __('Facebook account name.', 'wp-hackerspace');
        echo "<input name='fake_facebook' id='fake_facebook' value='{$option}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_identica_field()
    {
        $option = 'identica';
        $description = __('Identi.ca or StatusNet account, in the form yourspace@example.org', 'wp-hackerspace');
        echo "<input name='fake_identica' id='fake_identica' value='{$option}' class='regular-text ltr' type='email' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_foursquare_field()
    {
        $option = 'foursquare';
        $description = __('Foursquare ID, in the form 4d8a9114d85f3704eab301dc', 'wp-hackerspace');
        echo "<input name='fake_foursquare' id='fake_foursquare' value='{$option}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_ml_field()
    {
        $option = 'ml';
        $description = __('The e-mail address of your mailing list.', 'wp-hackerspace');
        echo "<input name='fake_ml' id='fake_ml' value='{$option}' class='regular-text ltr' type='email' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_jabber_field()
    {
        $option = 'jabber';
        $description = __('A public Jabber/XMPP multi-user chatroom in the form chatroom@conference.example.net', 'wp-hackerspace');
        echo "<input name='fake_jabber' id='fake_jabber' value='{$option}' class='regular-text ltr' type='email' />";
        echo "<p class='description'>{$description}</p>";
    }

}
