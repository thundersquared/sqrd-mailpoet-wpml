<?php

/**
 * Plugin Name: MailPoet WPML fixture
 * Description: MailPoet WPML fixture implementing some filters.
 * Version: PLUGIN_VERSION
 * Author: thundersquared
 * Author URI: https://thundersquared.com/
 * Text Domain: sqrd-mailpoet-wpml
 */

namespace sqrd\Fixtures;

defined('ABSPATH') || exit;

class MailPoetWPML
{
    public function __construct()
    {
        add_action('upgrader_process_complete', array($this, 'fix'));
        register_activation_hook(__FILE__, array($this, 'activation'));
    }

    public function activation()
    {
        // Check if MailPoet is installed and activated
        if (!is_plugin_active('mailpoet/mailpoet.php'))
        {
            // Deactivate self and die if no MailPoet
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die('This plugin requires MailPoet to be installed and activated.');
        }

        // Proceed with fix
        $this->fix();
    }

    public function fix()
    {
        $file = plugin_dir_path(__FILE__) . '../mailpoet/lib/Subscribers/ConfirmationEmailMailer.php';
        $data = file_get_contents($file);

        // Make safety copy
        copy($file, "$file.old");

        // Check if already fixed
        if (preg_match('/\\$email = apply_filters\\(\'mailpoet_before_confirmation_send\', \\$email\\);/ui', $data)
            == 0)
        {
            // Apply fixture
            $result = preg_replace('/\/\/ send email\\n    try {/ui', "\$email = apply_filters('mailpoet_before_confirmation_send', \$email);\n\n    // send email\n    try {", $data);

            // Save file
            file_put_contents($file, $result);
        }

        $file = plugin_dir_path(__FILE__) . '../mailpoet/lib/Subscribers/ConfirmationEmailMailer.php';
        $data = file_get_contents($file);

        // Check if already fixed
        if (preg_match('/\\$body = apply_filters\\(\'mailpoet_before_body_process\', \\$body\\);/ui', $data)
            == 0)
        {
            // Apply fixture
            $result = preg_replace('/\/\/ replace list of segments shortcode/ui', "\$body = apply_filters('mailpoet_before_body_process', \$body);\n\n    // replace list of segments shortcode", $data);

            // Save file
            file_put_contents($file, $result);
        }
    }
}

new MailPoetWPML();
