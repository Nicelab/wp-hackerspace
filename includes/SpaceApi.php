<?php
/**
 * Plugin Name: Hackerspace
 *
 * see http://spaceapi.net/documentation#documentation-tab-13
 */

class SpaceApi
{

    const API_VERSION = '0.13';

    public function __construct()
    {
        $this->spaceapi = new stdClass; // TODO create an external regular class ?
        $this->spaceapi->api = self::API_VERSION;
    }

    // save the space api settings
    public function save_spaceapi_settings()
    {
        //$spaceapi = $this->spaceapi;
    }

    // get space api infos from the settings
    public function get_spaceapi_settings()
    {
        $spaceapi = $this->spaceapi;
        $spaceapi->state->open = null;
        return $spaceapi;
    }

    //render json encoded Space Api infos
    public function spaceapi_json()
    {
        header('Access-Control-Allow-Origin: *;');
        header('Content-Type: application/json; charset='.get_option('blog_charset'));
        header('Cache-Control: no-cache;');
        $spaceapi = $this->get_spaceapi_settings();
        echo json_encode($spaceapi);
    }
}
