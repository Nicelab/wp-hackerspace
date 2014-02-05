<?php
/**
 * Plugin Name: Hackerspace
 */

// get the active settings tab, default to 'features'
if (isset($_GET['tab'])) {
    $active_tab = $_GET['tab'];
} else {
    $active_tab = 'features';
}

?>


<div class="wrap">
    <h2><?php _e('Hackerspace settings', 'wp-hackerspace') ?></h2>
    <?php settings_errors(); ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=hackerspace_options&tab=features" class="nav-tab <? if ($active_tab=='features') echo 'nav-tab-active'; ?>"><?php _e('Features', 'wp-hackerspace') ?></a>
        <a href="?page=hackerspace_options&tab=spaceapi" class="nav-tab <? if ($active_tab=='spaceapi') echo 'nav-tab-active'; ?>"><?php _e('Space Api', 'wp-hackerspace') ?></a>
    </h2>

    <form action="options.php" method="post">
    <?php
        if ($active_tab == 'features') {
            //settings_fields('hackerspace_settings');
            //do_settings_sections('hackerspace_settings');
        } elseif ($active_tab == 'spaceapi') {
            settings_fields('spaceapi_settings');
            do_settings_sections('spaceapi_settings');
        }
        submit_button();
    ?>
    </form>
</div>
