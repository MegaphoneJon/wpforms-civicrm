# WPForms CiviCRM

A WordPress plugin to submit WPForms webhooks in the format Form Processor expects.

## Installation

* You must already have WPForms and WPForms Webhooks plugins activated.
* Download to your WP plugins directory and activate.

## Using

* Your *Request URL* should be your REST endpoint, with the site key appended as the URL paramter `key`.  E.g. `https://mysite.org/wp-json/civicrm/v3/rest?key=mysitekey`.
* *Request Method* is **POST**, *Request Format* is **JSON**.
* Add a Request Header with a name `wpforms-civicrm`.  The value can be anything.
* There are two required request body fields you must create: `action` with the machine name of your Form Processor instance, and `api_key` with the API key you want to use for your submissions.
* Add all the other fields you want from your form to the body.  For most fields (Single Line Text, Paragraph Text, Email, Phone, Dropdown, Checkboxes, etc.) the name for each bodt you use for each field should match the name you use in Form Processor.
* For "Fancy Fields" (in WPForms Pro) that contain multiple subfields (e.g. name, address), the fields will be broken up before being passed to Form Processor.

If your Name field is called `myname`, the fields passed to Form Processor will be: `myname_first`, `myname_middle`, and `myname_last`.
If your Address field is called `myaddress` the fields passed will be: `myaddress_address1`, `myaddress_address2`, `myaddress_city`, `myaddress_state`, `myaddress_postal`, and `myaddress_country`.

![Screenshot](/images/screenshot.png)

## Why does this exist?

WPForms Webhooks is almost, but not quite, suited to send data to Form Processor.  This plugin changes the format of the body to match what Form Processor expects, and parses fields that aren't easily parsed on the CiviCRM side.

## Tips for use

* It's easiest to see the contents of your webhook by passing it to a site like [https://webhook.site](https://webhook.site).
* Because WPForms Webhooks sends asynchronously, it's often easiest to test by grabbing the payload from [https://webhook.site](https://webhook.site), saving it to a file `payload.json` and using `curl` to pass to Form Processor, e.g.:

```shell
# The "-b XDEBUG_SESSION=VSCODE" is only if you're running XDebug with VS Code, otherwise you can leave it off.
curl -X POST -H "Content-Type: application/json" -d @payload.json 'https://mysite.org/wp-json/civicrm/v3/rest?key=mysitekey' -b XDEBUG_SESSION=VSCODE
```

## Known issues

* Password fields can't be passed to CiviCRM.
* This is untested with the "Numbers", "Multiple Choice", "Number Slider", "Date/Time", "Website/URL", "File Upload", "Hidden Field", "Rating", "Rich Text" and payment fields. Most of these should work, but not all (without further improvement to the plugin).
