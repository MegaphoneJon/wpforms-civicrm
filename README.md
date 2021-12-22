# WPForms CiviCRM

A WordPress plugin to submit WPForms webhooks in the format Form Processor expects.

## Installation
* You must already have WPForms and WPForms Webhooks plugins activated.
* Download to your WP plugins directory and activate.

## Setup
* Your *Request URL* should be your REST endpoint, with the site key appended as the URL paramter `key`.  E.g. `https://mysite.org/wp-json/civicrm/v3/rest?key=mysitekey`.
* *Request Method* is **POST**, *Request Format* is **JSON**.
* There are two required request body fields you must create: **action** with the machine name of your Form Processor instance, and `api_key` with the API key you want to use for your submissions.
* Add all the other fields you want from your form.  The name you use for each field should match the name you use in Form Processor.

![Screenshot](/images/screenshot.png)

## Why does this exist?
WPForms Webhooks is almost, but not quite, suited to send data to Form Processor.  This plugin changes the format of the body to match what Form Processor expects.

## Tips for use
The "Fancy Fields" in WPForms are sent as a single field.  Either avoid their use, or use the "Other: Modify Value with Regular Expression" to pull out the individual pieces of data.

Here are some regular expressions I use:
* Split name into First and Last. If there is a space in any of the names, this will make the last word the last name, and the rest the first name:
```
/(.+)\s(\w+)$/
```
$1 is first name, $2 is last name.

* Split Address into constituent pieces:
```
/(.*)\|\|(.*)\|\|(.*), (.*)\|\|(.*)\|\|(.*)$/
```
$1 is Street Address, $2 is Street Address Line 2, $3 is City, $4 is State/Province, $5 is Postal Code, $6 is Country.
Use the "Address: Get country ID by name/ISO code" and "Address: Get state/province ID by name" actions to get the country and state_province IDs for generating addresses.
