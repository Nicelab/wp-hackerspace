<?php

/**
 * Define the custom post type for Projects
 *
 * @since 0.3
 */
class Post_Type_Project
{

    //TODO add a template page to display additional fields

    /** Constructor for the Post_Type_Project class */
    public function __construct()
    {
        // set the messages who appears on project update
        add_filter('post_updated_messages', array($this, 'post_updated_messages'));
        // set up default hidden meta boxes
        add_filter('default_hidden_meta_boxes', array($this, 'hide_meta_boxes'), 10, 2);
        // register the project meta box for additional fields
        add_action('add_meta_boxes', array($this, 'register_project_meta_box'));
        // add a save callback for saving additional fields
        add_action('save_post', array( $this, 'save_project_fields'));
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
            'description'        => 'Hackerspace projects',
            'public'             => true,
            'menu_position'      => 21,
            'menu_icon'          => 'dashicons-hammer',
            'capability_type'    => 'hackerspace_project',
            'capabilities'       => array(
                'edit_post'              => 'edit_hackerspace_projects',
                'read_post'              => 'read_hackerspace_project',
                'delete_post'            => 'delete_hackerspace_project',
                'edit_posts'             => 'edit_hackerspace_project',
                'edit_others_posts'      => 'edit_others_hackerspace_project',
                'publish_posts'          => 'publish_hackerspace_project',
                'read_private_posts'     => 'read_private_hackerspace_project',
                'delete_posts'           => 'delete_hackerspace_project',
                'delete_private_posts'   => 'delete_private_hackerspace_project',
                'delete_published_posts' => 'delete_published_hackerspace_project',
                'delete_others_posts'    => 'delete_others_hackerspace_project',
                'edit_private_posts'     => 'edit_private_hackerspace_project',
                'edit_published_posts'   => 'edit_published_hackerspace_project',
            ),
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
            'has_archive'         => true,
            'rewrite'            => array(
                'slug'               => __('projects', 'wp-hackerspace'),
            ),
        ));
    }

    /**
     * Set the messages who appears on update
     *
     * @param array $messages Default messages
     *
     * @return array $messages Messages for the Project post type
     */
    public function post_updated_messages($messages)
    {
        $post_id = get_post()->id;
        $post_date = get_post()->post_date;

        $messages['hackerspace_project'] = array(
            1 =>  sprintf(__('Project updated. <a href="%s">View project</a>', 'wp-hackerspace'), esc_url(get_permalink($post_id))),
            2 => __('Custom field updated.', 'wp-hackerspace'),
            5 =>  isset($_GET['revision']) ? sprintf(__('Project restored to revision from %s', 'wp-hackerspace'), wp_post_revision_title((int)$_GET['revision'], false)) : false,
            6 =>  sprintf(__('Project published. <a href="%s">View project</a>', 'wp-hackerspace'), esc_url(get_permalink($post_id))),
            7 =>  __('Project saved.', 'wp-hackerspace'),
            8 =>  sprintf(__('Project submitted. <a target="_blank" href="%s">Preview project</a>', 'wp-hackerspace'), esc_url(add_query_arg('preview', 'true', get_permalink($post_id)))),
            9 =>  sprintf(__('Project scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview project</a>', 'wp-hackerspace'), date_i18n(__('M j, Y @ G:i'), strtotime($post_date)), esc_url(get_permalink($post_id))),
            10 => sprintf(__('Project draft updated. <a target="_blank" href="%s">Preview project</a>', 'wp-hackerspace'), esc_url(add_query_arg('preview', 'true', get_permalink($post_id)))),
        );

        return $messages;
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

    /**
     * Hide by default author, slug and revisions meta boxes for project post types
     *
     * @param array     $hidden Hidden meta boxes
     * @param WP_Screen $screen WP_Screen object
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
        // Sanitize inputs
        $status = sanitize_text_field($_POST['project_status']);
        $contact = sanitize_text_field($_POST['project_contact']);
        $repository_url = sanitize_text_field($_POST['project_repository_url']);
        // Update the fields
        update_post_meta($post_id, '_project_status', $status);
        update_post_meta($post_id, '_project_contact', $contact);
        update_post_meta($post_id, '_project_repository_url', $repository_url);
    }

}
