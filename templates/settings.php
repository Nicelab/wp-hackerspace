<?php
/**
 * Template for the admin settings page
 */

// get the active settings tab, default to 'features'
if (isset($_GET['tab'])) {
    $active_tab = $_GET['tab'];
} else {
    $active_tab = 'features';
}

// get the spaceapi_enabled feature setting
$spaceapi_enabled = get_option('hackerspace_features')->spaceapi_enabled;

?>


<div class="wrap">
    <h2><?php _e('Hackerspace settings', 'wp-hackerspace') ?></h2>

    <h2 class="nav-tab-wrapper">
        <a href="?page=hackerspace_options&tab=features" class="nav-tab
        <?php
        if ($active_tab == 'features') {
            echo ' nav-tab-active';
        }
        ?>">
        <?php _e('Features', 'wp-hackerspace') ?></a>
        <?php
        // display the Space Api tab if the feature is enabled
        if ($spaceapi_enabled == true): ?>
        <a href="?page=hackerspace_options&tab=spaceapi" class="nav-tab
        <?php
        if ($active_tab == 'spaceapi') {
            echo ' nav-tab-active';
        }
        ?>">
        <?php
        _e('Space Api', 'wp-hackerspace');
        endif;
        ?></a>
    </h2>

    <form action="options.php" method="post">
    <?php
    if ($active_tab == 'features') {
        settings_fields('hackerspace_features');
        do_settings_sections('hackerspace_features');
    } elseif ($active_tab == 'spaceapi') {
        settings_fields('hackerspace_spaceapi');
        do_settings_sections('hackerspace_spaceapi');
    }
    submit_button();
    ?>
    </form>
</div>
