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
        // register the project meta boxe
        add_action('add_meta_boxes', array($this, 'register_project_meta_box'));
        // add a save callback for saving additional fields
        add_action('save_post', array( $this, 'save_project_fields'));
        // set up default hidden meta boxes
        add_filter('default_hidden_meta_boxes', array($this, 'hide_meta_boxes'), 10, 2);
    }

    /**
     * Hide by default author, slug and revisions meta boxes for projects
     *
     * @param  array     $hidden Hidden meta boxes
     * @param  WP_Screen $screen WP_Screen object
     *
     * @return array     $hidden
     */

    public function hide_meta_boxes($hidden, $screen)
    {
        $screen_id = $screen->id;
        if ($screen_id == 'hackerspace_project') {
            $hidden = array('revisionsdiv','slugdiv','authordiv');
        }

        return $hidden;
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

    /** Register the meta box used for the projects additional fields */
    public function register_project_meta_box()
    {
        add_meta_box(
            'project_repository_url',
            __('Additional informations', 'wp-hackerspace'),
            array($this, 'render_project_fields'),
            'hackerspace_project',
            'normal',
            'high'
            );
    }

//TODO add a template page to display addictional fields

    /**
     * Render the project additional fields in the meta box
     *
     * @param WP_Post $post WordPress post object
     */
    public function render_project_fields($post)
    {
        $status = get_post_meta($post->ID, '_project_status', true);
        $contact = get_post_meta($post->ID, '_project_contact', true);
        $repository_url = get_post_meta($post->ID, '_project_repository_url', true);
        wp_nonce_field('project_repository_url_meta_box', 'project_repository_url_meta_box_nonce');
        echo '<fieldset><legend><strong>'.__('Status', 'wp-hackerspace').'</strong></legend>';
        echo '<input type="text" name="project_status" value="'.esc_attr($status).'" class="regular-text" />';
        echo '&nbsp;<span class="description">'.__('The progress status of the project.', 'wp-hackerspace').'</fieldset></br>';
        echo '<fieldset><legend><strong>'.__('Contact person', 'wp-hackerspace').'</strong></legend>';
        echo '<input type="text" name="project_contact" value="'.esc_attr($contact).'" class="regular-text" />';
        echo '&nbsp;<span class="description">'.__('The person to contact.', 'wp-hackerspace').'</fieldset></br>';
        echo '<fieldset><legend><strong>'.__('Repository address', 'wp-hackerspace').'</strong></legend>';
        echo '<input type="url" name="project_repository_url" value="'.esc_attr($repository_url).'" class="regular-text code" />';
        echo '&nbsp;<span class="description">'.__('URL of the source code repository.', 'wp-hackerspace').'</fieldset>';
        // TODO add license field ?
    }

    /**
     * Save the post meta fields in the WordPress database
     *
     * @param int $post_id ID of the WordPress post
     */
    public function save_project_fields($post_id)
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
        $status = sanitize_text_field($_POST['project_status']);
        $contact = sanitize_text_field($_POST['project_contact']);
        $repository_url = sanitize_text_field($_POST['project_repository_url']);
        // Update the fields
        update_post_meta($post_id, '_project_status', $status);
        update_post_meta($post_id, '_project_contact', $contact);
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
                'author',
                'thumbnail',
                'excerpt',
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
