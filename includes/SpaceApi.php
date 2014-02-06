<?php
/**
 * Plugin Name: Hackerspace
 *
 * see http://spaceapi.net/documentation#documentation-tab-13
 */

class SpaceApi
{

    const API_VERSION = '0.13';

    // TODO use other options from wordress to populate the defaults
    // TODO move this in Settings.php ?
    // create the default (empty) spaceapi object for use with plugin settings forms
    public function set_default_spaceapi()
    {
        $spaceapi = new stdClass;
        $spaceapi->api = self::API_VERSION;
        $spaceapi->space = get_option('blogname');
        $spaceapi->logo = null;
        $spaceapi->url = get_option('siteurl');
        $spaceapi->location->address = null;
        $spaceapi->location->lat = null;
        $spaceapi->location->lon = null;
        $spaceapi->contact->email = get_option('admin_email');
        $spaceapi->contact->phone = null;
        $spaceapi->contact->sip = null;
        $spaceapi->contact->irc = null;
        $spaceapi->contact->twitter = null;
        $spaceapi->contact->facebook = null;
        $spaceapi->contact->identica = null;
        $spaceapi->contact->foursquare = null;
        $spaceapi->contact->ml = null;
        $spaceapi->contact->jabber = null;
        $spaceapi->issue_report_channels = array('email'); // hidden field

        return $spaceapi;
    }

    //return the space api infos
    public function get_spaceapi()
    {
        // get infos from settings
        $spaceapi = get_option('hackerspace_spaceapi');
        // TODO remove the null values
        // Add the api version
        $spaceapi->api = self::API_VERSION;
        // Add the open/close status
        $spaceapi->state->open = null;
        // TODO add projects
        return $spaceapi;
    }

    //render json encoded Space Api infos
    public function spaceapi_json()
    {
        header('Access-Control-Allow-Origin: *;');
        header('Content-Type: application/json; charset='.get_option('blog_charset'));
        header('Cache-Control: no-cache;');
        $spaceapi = $this->get_spaceapi();
        echo json_encode($spaceapi);
    }
}
