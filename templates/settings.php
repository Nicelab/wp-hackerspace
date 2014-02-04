<?php
/**
 * Plugin Name: Hackerspace
 */

?>


<div class="wrap">
    <h2><?php _e('Hackerspace settings', 'wp-hackerspace') ?></h2>
    <form action="options.php" method="post">
        <?php settings_fields('spaceapi_settings'); ?>
        <?php do_settings_sections('hackerspace_options'); ?>
        <?php submit_button(); ?>
    </form>
</div>
