<?php

/**
 * Define the custom post type for Projects
 *
 * @since 0.3
 */
class Post_Type_Project
{

    /**
     * Generate an object to display on top help tab
     *
     * @return stdClass object
     */
    public function help_tab()
    {
        $help_tab = new stdClass;
        $help_tab->id = 'wp-hackerspace-project';
        $help_tab->title = __('About Projects', 'wp-hackerspace');
        $help_tab->content = '<p>Project Post Type help text</p>';

        return $help_tab;
    }


    /** Register custom fields for the Project custom post type */
    public function projects_metaboxes()
    {
        //add_meta_box('project_url', 'Project url', 'project_location', 'projectss', 'side', 'default');
    }


    /** Register the custom post type for Projects */
    public function register_project_post_type()
    {
        register_post_type('hackerspace_project', array(
            'labels'             => array(
                'name'               => __('Projects', 'wp-hackerspace'),
                'singular_name'      => __('Project', 'wp-hackerspace'),
                'add_new'            => __('Add New', 'wp-hackerspace'),
                'add_new_item'       => __('Add New Project', 'wp-hackerspace'),
                'edit_item'          => __('Edit Project', 'wp-hackerspace'),
                'new_item'           => __('New Project', 'wp-hackerspace'),
                'all_items'          => __('All Projects', 'wp-hackerspace'),
                'view_item'          => __('View Project', 'wp-hackerspace'),
                'search_items'       => __('Search Projects', 'wp-hackerspace'),
                'not_found'          => __('No projects found', 'wp-hackerspace'),
                'not_found_in_trash' => __('No projects found in Trash', 'wp-hackerspace'),
                'parent_item_colon'  => '',
                'menu_name'          => __('Projects', 'wp-hackerspace'),
            ),
            'public'             => true,
            'menu_position'      => 21,
            'menu_icon'          => 'dashicons-hammer',
            //'capabilities' // TODO add user rights
            'supports'           => array(
                'title',
                'editor',
                'thumbnail',
                'custom-fields',
                'revisions',
            ),
            'taxonomies'         => array(
                'category',
                'post_tag',
            ),
            'rewrite'            => array(
                'slug'               => __('project', 'wp-hackerspace'), // hesitating between 'project' and 'projects'
            ),
        ));
    }

}
