<?php

/**
 * Get the Space Api infos and generate the json file
 *
 * For more information about the Space Api see
 * see http://spaceapi.net/documentation#documentation-tab-13
 *
 * @since 0.3
 */
class Space_Api
{

    const API_VERSION = '0.13';

    /**
     * Create the default Space Api values
     *
     * @return stdClass object
     */
    public function set_default_spaceapi()
    {
        $spaceapi = new stdClass;
        $spaceapi->api = self::API_VERSION;
        $spaceapi->space = get_option('blogname');
        $spaceapi->logo = '';
        $spaceapi->url = get_option('siteurl');
        $spaceapi->location->address = '';
        $spaceapi->location->lat = null;
        $spaceapi->location->lon = null;
        $spaceapi->contact->email = get_option('admin_email');
        $spaceapi->contact->phone = '';
        $spaceapi->contact->sip = '';
        $spaceapi->contact->irc = '';
        $spaceapi->contact->twitter = '';
        $spaceapi->contact->facebook = '';
        $spaceapi->contact->identica = '';
        $spaceapi->contact->foursquare = '';
        $spaceapi->contact->ml = '';
        $spaceapi->contact->jabber = '';
        $spaceapi->issue_report_channels = array('email');
        $spaceapi->cache->schedule = 'm.05';

        return $spaceapi;
    }

    /**
     * Get the space api infos
     *
     * @return stdClass object
     */
    private function get_spaceapi()
    {
        // get infos from settings
        $spaceapi = get_option('hackerspace_spaceapi');
        // remove not required empty values
        if ($spaceapi->location->address == '') {
            unset($spaceapi->location->address);
        }
        if ($spaceapi->contact->phone == '') {
            unset($spaceapi->contact->phone);
        }
        if ($spaceapi->contact->sip == '') {
            unset($spaceapi->contact->sip);
        }
        if ($spaceapi->contact->irc == '') {
            unset($spaceapi->contact->irc);
        }
        if ($spaceapi->contact->twitter == '') {
            unset($spaceapi->contact->twitter);
        }
        if ($spaceapi->contact->facebook == '') {
            unset($spaceapi->contact->facebook);
        }
        if ($spaceapi->contact->identica == '') {
            unset($spaceapi->contact->identica);
        }
        if ($spaceapi->contact->foursquare == '') {
            unset($spaceapi->contact->foursquare);
        }
        if ($spaceapi->contact->ml == '') {
            unset($spaceapi->contact->ml);
        }
        if ($spaceapi->contact->jabber == '') {
            unset($spaceapi->contact->jabber);
        }

        // Add the open/close status
        $Space_State = new Space_State();
        $spaceapi->state->open = $Space_State->is_open();
        // Add the default Wordpress Blog rss2 feed
        $spaceapi->feeds->blog->type = 'rss';
        $spaceapi->feeds->blog->url = get_bloginfo('rss2_url');
        // add projects list permalinks from Project post type
        $projects_query = new WP_Query(array(
            'post_type' => 'hackerspace_project',
            'status'    => 'publish',
            'nopaging'  => true,
        ));
        if ($projects_query->have_posts()) {
            $spaceapi->projects = array();
            while ($projects_query->have_posts()) {
                $projects_query->the_post();
                $spaceapi->projects[] = get_permalink();
            }
        }

        return $spaceapi;
    }

    /** Render json encoded Space Api infos */
    public function spaceapi_json()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        header('Cache-Control: no-cache');
        $spaceapi = $this->get_spaceapi();
        echo json_encode($spaceapi);
    }
}
