<?php
/**
 * Plugin Name: Hackerspace
 *
 * see http://spaceapi.net/documentation#documentation-tab-13
 */

class SpaceApi
{

    const API_VERSION = '0.13';


    // get space api infos from the settings
    public function get_spaceapi_settings()
    {
        $spaceapi = new stdClass;
        $spaceapi->api = self::API_VERSION;
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
