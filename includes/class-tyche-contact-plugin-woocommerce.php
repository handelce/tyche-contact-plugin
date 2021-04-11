<?php

/**
 * Woocommerce Actions for Tyche Contact Plugin
 *
 * @link       https://handelce.com
 * @since      1.0.0
 *
 * @package    Tyche_Contact_Plugin
 * @subpackage Tyche_Contact_Plugin/includes
 */

/**
 *
 * This class defines code for Woocommerce Actions for Tyche Contact Plugin.
 *
 * @since      1.0.0
 * @package    Tyche_Contact_Plugin
 * @subpackage Tyche_Contact_Plugin/includes
 * @author     Handel CE <handel.ce@gmail.com>
 */


function action_woocommerce_new_order($order_id)
{
    $options = get_option(TYCHE_CONTACT_PLUGIN_NAME);

    // get order object and order details
    $order = wc_get_order($order_id);

    // get customer details from billing details
    $first_name = $order->get_billing_first_name();
    $last_name = $order->get_billing_last_name();
    $email_address = $order->get_billing_email();

    //create new contact in MailJet
    connect_to_mailjet($options, 'contact', 'POST', array("Name" => strval($first_name . " " . $last_name), "Email" => strval($email_address)));

    // send email along with first and last name

    connect_to_mailjet($options, '', 'SEND-EMAIL', array("to_name" => strval($first_name . " " . $last_name), "to_email" => strval($email_address), "subject" => $first_name . ", Thank you for your order", "text_part" => "Hello " . $first_name . " " . $last_name . ". This is an email thanking you for your order. We also wish to inform you that your Contact Information has been created on MailJet.", "html_part" => "Hello " . $first_name . " " . $last_name . ". This is an email thanking you for your order. We also wish to inform you that your Contact Information has been created on MailJet."));
}

// add the action 
add_action('woocommerce_thankyou', 'action_woocommerce_new_order', 10, 3);
