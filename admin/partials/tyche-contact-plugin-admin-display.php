<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://handelce.com
 * @since      1.0.0
 *
 * @package    Tyche_Contact_Plugin
 * @subpackage Tyche_Contact_Plugin/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->


<div class="wrap">

    <h2 style="padding:20px 0;"><?php echo esc_html(get_admin_page_title()); ?></h2>

    <form method="post" name="tyche-contact-plugin-options" action="options.php">

        <?php
        $options = get_option($this->plugin_name);

        $api_key = (isset($_POST['api-key']) && !empty((isset($_POST['api-key']) && !empty($_POST['api-key'])))) ? sanitize_text_field($_POST['api-key']) : $options['api-key'];

        $secret = (isset($_POST['secret']) && !empty((isset($_POST['secret']) && !empty($_POST['secret'])))) ? sanitize_text_field($_POST['secret']) : $options['secret'];
        ?>

        <?php
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
        ?>

        <fieldset style="margin-bottom: 30px;">
            <legend class="screen-reader-text"><span>Mailjet API Settings</span></legend>
            <span style="display:block;margin-bottom:10px;"><?php esc_attr_e('Enter your MailJet API Key from mailjet.com', $this->plugin_name); ?></span>
            <label for="<?php echo $this->plugin_name; ?>-api-key">
                <input type="text" id="<?php echo $this->plugin_name; ?>-api-key" name="<?php echo $this->plugin_name; ?>[api-key]" value="<?php echo $api_key; ?>" />
            </label>
        </fieldset>

        <fieldset>
            <legend class="screen-reader-text"><span>Mailjet API Secret</span></legend>
            <span style="display:block;margin-bottom:10px;"><?php esc_attr_e('Enter your MailJet Secret Token', $this->plugin_name); ?></span>
            <label for="<?php echo $this->plugin_name; ?>-secret">
                <input type="text" id="<?php echo $this->plugin_name; ?>-secret" name="<?php echo $this->plugin_name; ?>[secret]" value="<?php echo $secret; ?>" />
            </label>
        </fieldset>

        <?php submit_button('Save Settings', 'primary', 'submit', TRUE); ?>

    </form>
    <p><em>For technical complaints, contact the developer: handel.emezue@gmail.com</em></p>

    <?php
    if (!empty($options['api-key']) && !empty($options['secret'])) {

        // test Mailjet API Connection and display an error if connection is unsuccessful
        $response = connect_to_mailjet($options, 'contact');
        $data = json_decode($response, true);

        if ((int) $data['Count'] <= 0) {
            echo "<p style='background:red;padding:20px 50px;color:#FFF'>Could not connect to the MailJet Server. Please check your MaiJet API Key and Secret Key.</p>";
        } else {

            // display list of contacts in an HTML Table

    ?>

            <h2>List of Saved Contacts on Mailjet</h2>
            <table border="1" style="border-collapse:collapse">
                <tr>
                    <td style="padding:20px;">Name</td>
                    <td style="padding:20px;">Email Address</td>
                </tr>

                <?php
                foreach ($data['Data'] as $contact) { ?>
                    <tr>
                        <td style="padding:20px;"><?php echo $contact['Name']; ?></td>
                        <td style="padding:20px;"><?php echo $contact['Email']; ?></td>
                    </tr>
                <?php } ?>

            </table>

    <?php

        }
    }

    ?>

</div>