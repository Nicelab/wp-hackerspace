<?php
/**
 * Plugin Name: Hackerspace
 *
 * see http://spaceapi.net/documentation#documentation-tab-13
 */

class SpaceApi
{

    const API_VERSION = '0.13';

    // TODO move this in Settings.php ?
    // create the default (empty) spaceapi object for use with plugin settings forms
    public function set_default_spaceapi()
    {
        $spaceapi = new stdClass;
        $spaceapi->api = self::API_VERSION;
        $spaceapi->space = get_option('blogname');
        $spaceapi->logo = "";
        $spaceapi->url = get_option('siteurl');
        $spaceapi->location->address = "";
        $spaceapi->location->lat = null;
        $spaceapi->location->lon = null;
        $spaceapi->contact->email = get_option('admin_email');
        $spaceapi->contact->phone = "";
        $spaceapi->contact->sip = "";
        $spaceapi->contact->irc = "";
        $spaceapi->contact->twitter = "";
        $spaceapi->contact->facebook = "";
        $spaceapi->contact->identica = "";
        $spaceapi->contact->foursquare = "";
        $spaceapi->contact->ml = "";
        $spaceapi->contact->jabber = "";
        $spaceapi->issue_report_channels = array('email');

        return $spaceapi;
    }

    //return the space api infos
    public function get_spaceapi()
    {
        // get infos from settings
        $spaceapi = get_option('hackerspace_spaceapi');
        // remove not required empty values
        if ($spaceapi->location->address == "") unset($spaceapi->location->address);
        if ($spaceapi->contact->phone == "") unset($spaceapi->contact->phone);
        if ($spaceapi->contact->sip == "") unset($spaceapi->contact->sip);
        if ($spaceapi->contact->irc == "") unset($spaceapi->contact->irc);
        if ($spaceapi->contact->twitter == "") unset($spaceapi->contact->twitter);
        if ($spaceapi->contact->facebook == "") unset($spaceapi->contact->facebook);
        if ($spaceapi->contact->identica == "") unset($spaceapi->contact->irc);
        if ($spaceapi->contact->foursquare == "") unset($spaceapi->contact->foursquare);
        if ($spaceapi->contact->ml == "") unset($spaceapi->contact->ml);
        if ($spaceapi->contact->jabber == "") unset($spaceapi->contact->jabber);

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
