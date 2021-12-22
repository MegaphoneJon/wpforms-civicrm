<?php
/**
 * Plugin Name: WPForms CiviCRM
 * Plugin URI: http://github.com/MegaphoneJon/wpforms-civicrm
 * Description: Format WPForms webhooks to be suitable for use with CiviCRM's Form Processor extension.
 * Version: 1.0
 * Author: Megaphone Technology Consulting
 * Author URI: https://www.megaphonetech.com
 */

add_filter('wpforms_webhooks_process_fill_http_body_params_value', 'wpforms_civicrm_format_for_form_processor');

function wpforms_civicrm_format_for_form_processor($oldParams) {
  $newParams['entity'] = 'FormProcessor';
  $newParams['action'] = $oldParams['action'];
  $newParams['api_key'] = $oldParams['api_key'];
  unset($oldParams['api_key']);
  unset($oldParams['action']);
  $newParams['json'] = json_encode($oldParams);
  return $newParams;
}
