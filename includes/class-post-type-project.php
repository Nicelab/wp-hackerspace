<?php

/**
 * Define the custom post type for Projects
 *
 * @since 0.3
 */
class Post_Type_Project
{

    /** Constructor for the Post_Type_Project class */
    public function __construct()
    {
        // register the project meta boxes
        add_action('add_meta_boxes', array($this, 'register_project_meta_boxes'));
        // add a save callback for saving additional fields
        add_action('save_post', array( $this, 'save_meta_boxes'));
    }

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

    /** Register meta boxes for the Project custom post type */
    public function register_project_meta_boxes()
    {
        add_meta_box(
            'project_repository_url',
            __('Repository url', 'wp-hackerspace'),
            array($this, 'render_repository_url_meta_box'),
            'hackerspace_project',
            'normal',
            'high'
            );
    }

//TODO all additional fields in one box
//Additional fields : author / person to contact / status / license /
//TODO add a template page to display addictional fields

    /**
     * Render the repository url field
     *
     * @param WP_Post $post WordPress post object.
     */
    public function render_repository_url_meta_box($post)
    {
        $value = get_post_meta($post->ID, '_project_repository_url', true);
        wp_nonce_field('project_repository_url_meta_box', 'project_repository_url_meta_box_nonce');
        //echo '';
        echo '<input type="url" name="project_repository_url" value="'.esc_attr($value).'" class="regular-text code" />';
        echo '<p class="description">'.__('URL of the code source repository.', 'wp-hackerspace').'</p>';
    }

    /**
     * Save the post meta fields in the WordPress database
     *
     * @param int $post_id ID of the WordPress post.
     */
    public function save_meta_boxes($post_id)
    {
        // Check the nonce is set
        if (! isset($_POST['project_repository_url_meta_box_nonce'])) {
            return $post_id;
        }
        // Check the nonce is valid
        if (! wp_verify_nonce($_POST['project_repository_url_meta_box_nonce'], 'project_repository_url_meta_box')) {
            return $post_id;
        }
        // Do not save in the database if it's an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        // TODO add user rights checks
        // Sanitize inputs
        $repository_url = sanitize_text_field($_POST['project_repository_url']);
        // Update the field
        update_post_meta($post_id, '_project_repository_url', $repository_url);
    }

    ///** Update various user messages*/
    //public function post_updated_messages()
    //{
    //}

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
