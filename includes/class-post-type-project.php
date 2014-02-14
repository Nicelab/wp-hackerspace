<?php

/**
 * Define the custom post type for Projects
 *
 * @since 0.3
 */
class Post_Type_Project
{

    /** Register the custom post type for Projects */
    public function register_project_post_type()
    {
        register_post_type('space_project', array(
            'labels'            => array(
                'name'          => (__('Projects', 'wp-hackerspace')),
                'singular_name' => (__('Project', 'wp-hackerspace')),
            ),
            'public'            => true,
            'menu_position'     => 21,
            'menu_icon'         => 'dashicons-hammer',
        ));
    }

}
