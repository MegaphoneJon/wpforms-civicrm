<?php
/**
 * Plugin Name: WPForms CiviCRM
 * Plugin URI: http://github.com/MegaphoneJon/wpforms-civicrm
 * Description: Format WPForms webhooks to be suitable for use with CiviCRM's Form Processor extension.
 * Version: 1.1
 * Author: Megaphone Technology Consulting
 * Author URI: https://www.megaphonetech.com
 */


add_filter('wpforms_webhooks_process_fill_http_header_params_value', 'webforms_civicrm_enable_integration', 10, 2);

function webforms_civicrm_enable_integration($dontCare, $params) {
  if ($params['custom_wpforms-civicrm']['value'] || FALSE) {
    add_filter('wpforms_webhooks_process_fill_http_body_params_value', 'wpforms_civicrm_format_for_form_processor', 10, 3);
  }
}

function wpforms_civicrm_format_for_form_processor($filledParams, $params, $processObject) {
  // Configure the entity/action/api_key, which go outside the main payload.
  $newParams['entity'] = 'FormProcessor';
  $newParams['action'] = $filledParams['action'];
  $newParams['api_key'] = $filledParams['api_key'];
  unset($filledParams['api_key']);
  unset($filledParams['action']);
  $objectFieldData = $processObject->get_fields();
  // Iterate through the remaining params, adding them to a payload array.
  $payload = [];
  foreach ($filledParams as $key => $submittedValue) {
    $fieldType = $objectFieldData[$params[$key]]['type'];
    switch ($fieldType) {
      case 'text':
      case 'textarea':
      case 'email':
      case 'phone':
      case 'url':
        // These need no manipulation. Setting them here and not in default to be explicit about which fields are supported.
        $payload[$key] = $submittedValue;
        break;

      case 'name':
      case 'address':
        $parsedValues = wpforms_civicrm_parse_field($objectFieldData[$params[$key]]);
        foreach ($parsedValues as $parsedKey => $parsedValue) {
          $payload["{$key}_{$parsedKey}"] = $parsedValue;
        }
        break;

      case 'checkbox':
      case 'select':
        $parsedValues = wpforms_civicrm_parse_multiselect($submittedValue);
        $payload[$key] = $parsedValues;
        break;

      default:
        // For any unsupported fields.
        $payload[$key] = $submittedValue;
    }
  }
  $newParams['json'] = json_encode($payload);
  return $newParams;
}

/**
 * Separate compound fields into component parts.
 */
function wpforms_civicrm_parse_field($fieldArray) {
  foreach ($fieldArray as $key => $value) {
    // Ignore these keys.
    if (in_array($key, ['name', 'value', 'id', 'type'])) {
      continue;
    }
    $return[$key] = $value;
  }
  return $return;
}

/**
 * Convert multiselect to an array.
 */
function wpforms_civicrm_parse_multiselect($value) {
  $return = explode('||', $value);
  return $return;
}
