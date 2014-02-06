<?php
/**
 * Plugin Name: Hackerspace
 */


class Settings
{

    public function __construct()
    {
    }

    // validate the Space api settings
    public function spaceapi_settings_validate($input)
    {
        // input options are in an arry, we prefer stdClass object
        $output = json_decode(json_encode($input));
        // sanitization
        $output->location->lat = (float)$output->location->lat; // html form have saved this as text instead off numbers
        $output->location->lon = (float)$output->location->lon;
        // TODO add validation


        return $output;
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

    public function spaceapi_other_section()
    {
    }

    // render the Space Api form fields
    // TODO we must create a generic function for this, or search if there is a WordPress official one

    public function spaceapi_api_field() // readonly field
    {
        $options = get_option('hackerspace_spaceapi');
        echo "<input name='hackerspace_spaceapi[api]' value='{$options->api}' class='regular-text' type='text' readonly />";
    }

    // TODO issue report chanel is readonly and set up to 'email' for now, we need change this to combo boxes in future versions
    public function spaceapi_issue_report_channel_field() //read only field
    {
        $options = get_option('hackerspace_spaceapi');
        echo "<input name='hackerspace_spaceapi[issue_report_channels][0]' value='{$options->issue_report_channels[0]}' type='text' readonly />";
    }

    public function spaceapi_space_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('The name of your space.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[space]' value='{$options->space}' class='regular-text' type='text' required='required' />";
        echo "<p class='description'>{$description}</p></br>";
        print_r($options);
    }

    public function spaceapi_url_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('URL to your space website.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[url]' value='{$options->url}' class='regular-text code' type='url' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_logo_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('URL to your space logo.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[logo]' value='{$options->logo}' class='regular-text code' type='url' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_address_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('The postal address of your space. Example: Netzladen e.V., Breite Straße 74, 53111 Bonn, Germany', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[location][address]' value='{$options->location->address}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    // TODO test coma/point, add step and max min
    public function spaceapi_lat_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Latitude of your space location, in degree with decimal places. Use positive values for locations north of the equator, negative values for locations south of equator.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[location][lat]' value='{$options->location->lat}' class='small-text' type='number' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_lon_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Longitude of your space location, in degree with decimal places. Use positive values for locations west of Greenwich, and negative values for locations east of Greenwich.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[location][lon]' value='{$options->location->lon}' class='small-text' type='number' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    // email is required for now, because of issue_report_channels_field set up to default to this value
    public function spaceapi_email_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('E-mail address for contacting your space.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][email]' value='{$options->contact->email}' class='regular-text ltr' type='email' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_phone_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Phone number, including country code with a leading plus sign. Example: +1 800 555 4567', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][phone]' value='{$options->contact->phone}' class='regular-text' type='tel' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_sip_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('URI for Voice-over-IP via SIP. Example: sip:yourspace@sip.example.org', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][sip]' value='{$options->contact->sip}' class='regular-text code' type='url' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_irc_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('URL of the IRC channel, in the form irc://example.org/#channelname', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][irc]' value='{$options->contact->irc}' class='regular-text code' type='url' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_twitter_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Twitter handle, with leading @', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][twitter]' value='{$options->contact->twitter}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_facebook_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Facebook account name.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][facebook]' value='{$options->contact->facebook}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_identica_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Identi.ca or StatusNet account, in the form yourspace@example.org', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][identica]' value='{$options->contact->identica}' class='regular-text ltr' type='email' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_foursquare_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Foursquare ID, in the form 4d8a9114d85f3704eab301dc', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][foursquare]' value='{$options->contact->foursquare}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_ml_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('The e-mail address of your mailing list.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][ml]' value='{$options->contact->ml}' class='regular-text ltr' type='email' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function spaceapi_jabber_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('A public Jabber/XMPP multi-user chatroom in the form chatroom@conference.example.net', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][jabber]' value='{$options->contact->jabber}' class='regular-text ltr' type='email' />";
        echo "<p class='description'>{$description}</p>";
    }

}
