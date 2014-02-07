<?php
/**
 * Plugin Name: Hackerspace
 */


class SpaceApiSettings
{

    public function __construct()
    {
    }

    // whitelist the Space Api settings
    public function register_settings()
    {
        register_setting('hackerspace_spaceapi', 'hackerspace_spaceapi', array($this, 'settings_validate'));

        add_settings_section('about_section', null, null, 'hackerspace_spaceapi');
        add_settings_section('main_section', __('Main informations', 'wp-hackerspace'), array($this, 'main_section'), 'hackerspace_spaceapi');
        add_settings_section('location_section', __('Location', 'wp-hackerspace'), array($this, 'location_section'), 'hackerspace_spaceapi');
        add_settings_section('contact_section', __('Contact', 'wp-hackerspace'), array($this, 'contact_section'), 'hackerspace_spaceapi');
        add_settings_section('other_section', __('Other', 'wp-hackerspace'), array($this, 'other_section'), 'hackerspace_spaceapi');

        add_settings_field('api', __('Space Api version', 'wp-hackerspace'), array($this, 'api_field'), 'hackerspace_spaceapi', 'about_section');
        add_settings_field('space', __('Space name', 'wp-hackerspace'), array($this, 'space_field'), 'hackerspace_spaceapi', 'main_section');
        add_settings_field('url', __('Space url', 'wp-hackerspace'), array($this, 'url_field'), 'hackerspace_spaceapi', 'main_section');
        add_settings_field('logo', __('Logo url', 'wp-hackerspace'), array($this, 'logo_field'), 'hackerspace_spaceapi', 'main_section');
        add_settings_field('address', __('Address', 'wp-hackerspace'), array($this, 'address_field'), 'hackerspace_spaceapi', 'location_section');
        add_settings_field('lat', __('Latitude', 'wp-hackerspace'), array($this, 'lat_field'), 'hackerspace_spaceapi', 'location_section');
        add_settings_field('lon', __('Longitude', 'wp-hackerspace'), array($this, 'lon_field'), 'hackerspace_spaceapi', 'location_section');
        add_settings_field('email', __('Email', 'wp-hackerspace'), array($this, 'email_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('phone', __('Phone', 'wp-hackerspace'), array($this, 'phone_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('sip', __('SIP', 'wp-hackerspace'), array($this, 'sip_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('irc', __('IRC', 'wp-hackerspace'), array($this, 'irc_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('twitter', __('Twitter', 'wp-hackerspace'), array($this, 'twitter_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('facebook', __('Facebook', 'wp-hackerspace'), array($this, 'facebook_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('identica', __('Identica', 'wp-hackerspace'), array($this, 'identica_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('foursquare', __('Foursquare', 'wp-hackerspace'), array($this, 'foursquare_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('ml', __('Mailling list', 'wp-hackerspace'), array($this, 'ml_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('jabber', __('Jabber', 'wp-hackerspace'), array($this, 'jabber_field'), 'hackerspace_spaceapi', 'contact_section');
        add_settings_field('issue_report_channel', __('Issue report channel', 'wp-hackerspace'), array($this, 'issue_report_channel_field'), 'hackerspace_spaceapi', 'other_section');
    }

    // validate the Space Api settings
    public function settings_validate($input)
    {
        // input options are in an array, we use a stdClass object
        $output = json_decode(json_encode($input));
        // sanitization
        $output->location->lat = (float)$output->location->lat; // html form have saved this as text instead off numbers
        $output->location->lon = (float)$output->location->lon;
        // TODO add validation
        return $output;
    }

    public function help_tab()
    {
        $help_tab = new stdClass;
        $help_tab->id = 'wp-hackerspace-spaceapi';
        $help_tab->title = __('Space Api', 'wp-hackerspace');
        $help_tab->content = '<p>Space Api help text</p>';
        return $help_tab;
    }

    // render the main section description text
    public function main_section()
    {
        _e('Main informations about your space.', 'wp-hackerspace');
    }

    public function location_section()
    {
        _e('Position data such as a postal address or geographic coordinates.', 'wp-hackerspace');
    }

    public function contact_section()
    {
        _e('Contact information about your space. You must define at least one.', 'wp-hackerspace');
    }

    public function other_section()
    {
    }

    // render the Space Api form fields
    // TODO we must create a generic function for this, or search if there is a WordPress official one

    public function api_field() // readonly field
    {
        $options = get_option('hackerspace_spaceapi');
        echo "<input name='hackerspace_spaceapi[api]' value='{$options->api}' class='regular-text' type='text' readonly />";
    }

    public function space_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('The name of your space.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[space]' value='{$options->space}' class='regular-text' type='text' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function url_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('URL to your space website.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[url]' value='{$options->url}' class='regular-text code' type='url' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function logo_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('URL to your space logo.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[logo]' value='{$options->logo}' class='regular-text code' type='url' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function address_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('The postal address of your space. Example: Netzladen e.V., Breite Straße 74, 53111 Bonn, Germany', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[location][address]' value='{$options->location->address}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    // TODO test coma/point, add step and max min
    public function lat_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Latitude of your space location, in degree with decimal places. Use positive values for locations north of the equator, negative values for locations south of equator.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[location][lat]' value='{$options->location->lat}' class='small-text' type='number' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function lon_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Longitude of your space location, in degree with decimal places. Use positive values for locations west of Greenwich, and negative values for locations east of Greenwich.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[location][lon]' value='{$options->location->lon}' class='small-text' type='number' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    // email is required for now, because of issue_report_channels_field set up to default to this value
    public function email_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('E-mail address for contacting your space.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][email]' value='{$options->contact->email}' class='regular-text ltr' type='email' required='required' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function phone_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Phone number, including country code with a leading plus sign. Example: +1 800 555 4567', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][phone]' value='{$options->contact->phone}' class='regular-text' type='tel' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function sip_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('URI for Voice-over-IP via SIP. Example: sip:yourspace@sip.example.org', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][sip]' value='{$options->contact->sip}' class='regular-text code' type='url' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function irc_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('URL of the IRC channel, in the form irc://example.org/#channelname', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][irc]' value='{$options->contact->irc}' class='regular-text code' type='url' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function twitter_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Twitter handle, with leading @', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][twitter]' value='{$options->contact->twitter}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function facebook_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Facebook account name.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][facebook]' value='{$options->contact->facebook}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function identica_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Identi.ca or StatusNet account, in the form yourspace@example.org', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][identica]' value='{$options->contact->identica}' class='regular-text ltr' type='email' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function foursquare_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('Foursquare ID, in the form 4d8a9114d85f3704eab301dc', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][foursquare]' value='{$options->contact->foursquare}' class='regular-text' type='text' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function ml_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('The e-mail address of your mailing list.', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][ml]' value='{$options->contact->ml}' class='regular-text ltr' type='email' />";
        echo "<p class='description'>{$description}</p>";
    }

    public function jabber_field()
    {
        $options = get_option('hackerspace_spaceapi');
        $description = __('A public Jabber/XMPP multi-user chatroom in the form chatroom@conference.example.net', 'wp-hackerspace');
        echo "<input name='hackerspace_spaceapi[contact][jabber]' value='{$options->contact->jabber}' class='regular-text ltr' type='email' />";
        echo "<p class='description'>{$description}</p>";
    }

    // TODO issue report chanel is readonly and set up to 'email' for now, we need change this to combo boxes in future versions
    public function issue_report_channel_field() //read only field
    {
        $options = get_option('hackerspace_spaceapi');
        echo "<input name='hackerspace_spaceapi[issue_report_channels][0]' value='{$options->issue_report_channels[0]}' type='text' readonly />";
    }

}