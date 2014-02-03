<?php
/**
 * Plugin Name: Hackerspace
 *
 * Space Api json feed rendered at adress like http://example.org/?feed=spaceapi
 */

//render json encoded Space Api infos
class SpaceApiJson
{
    public function spaceapi_json()
    {
        header('Access-Control-Allow-Origin: *;');
        header('Content-Type: application/json; charset='.get_option('blog_charset'));
        header('Cache-Control: no-cache;');

        $spaceapi = new stdClass;
        $spaceapi->api = '0.13';
        $spaceapi->state->open = null;

        echo json_encode($spaceapi);
    }
}
