<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin
 *
 * @link              https://handelce.com
 * @since             1.0.0
 * @package           Tyche_Contact_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       Tyche Contact Plugin
 * Plugin URI:        https://handelce.com
 * Description:       Plugin that will create a new contact in Mailjet when an order is placed in WooCommerce
 * Version:           1.0.0
 * Author:            Handel CE
 * Author URI:        https://handelce.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tyche-contact-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('TYCHE_CONTACT_PLUGIN_VERSION', '1.0.0');
define('TYCHE_CONTACT_PLUGIN_NAME', 'tyche-contact-plugin');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tyche-contact-plugin-activator.php
 */
function activate_tyche_contact_plugin()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-tyche-contact-plugin-activator.php';
	Tyche_Contact_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tyche-contact-plugin-deactivator.php
 */
function deactivate_tyche_contact_plugin()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-tyche-contact-plugin-deactivator.php';
	Tyche_Contact_Plugin_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_tyche_contact_plugin');
register_deactivation_hook(__FILE__, 'deactivate_tyche_contact_plugin');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-tyche-contact-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tyche_contact_plugin()
{

	$plugin = new Tyche_Contact_Plugin();
	$plugin->run();
}


// connection to mailjet
function connect_to_mailjet($token, $params, $request = 'GET', $post_fields = '')
{
	$curl = curl_init();
	$token = base64_encode($token['api-key'] . ':' . $token['secret']);

	if ($request == 'GET') {
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.mailjet.com/v3/REST/' . $params,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Basic ' . $token
			),
		));
	} else if ($request == 'POST') {

		if (is_array($post_fields)) {

			$fields = '{"IsExcludedFromCampaigns":false,'
				. '"Name":"' . $post_fields['Name'] . '",'
				. '"Email":"' . $post_fields['Email'] . '"}';

			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://api.mailjet.com/v3/REST/' . $params,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $fields,
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
					'Authorization: Basic ' . $token
				),
			));
		}
	} else if ($request == 'SEND-EMAIL') {

		if (is_array($post_fields)) {

			$fields = '{"Messages": [
				{
					"From": {
						"Email": "tyche@tyche.confinotes.com",
						"Name": "Tyche Contact WordPress Plugin"
					},
					"To": [
						{
							"Email": "' . $post_fields['to_email'] . '",
							"Name": "' . $post_fields['to_name'] . '"
						}
					],
					"Subject": "' . $post_fields['subject'] . '",
					"TextPart": "' . $post_fields['text_part'] . '",
					"HTMLPart": "' . $post_fields['html_part'] . '"
				}
			]
		  }';

			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://api.mailjet.com/v3.1/send',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $fields,
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json',
					'Authorization: Basic ' . $token
				),
			));
		}
	}

	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}
run_tyche_contact_plugin();
