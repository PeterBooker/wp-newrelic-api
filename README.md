# WP NewRelic

WP NewRelic handles communication between WordPress and the NewRelic API v2. It can be added to your theme/plugin or the /mu-plugins/ folder. It is designed to allow easy fetching of data from NewRelic to use and/or display on your WordPress site.

You can see more information about NewRelic's v2 API here: [https://rpm.newrelic.com/api/explore](https://rpm.newrelic.com/api/explore)

## Authentication

An API key is used for authentication. You can find instructions on obtaining your API key [here](https://docs.newrelic.com/docs/apis/api-key).

## Supported Endpoints

Not all Endpoints are supported yet, but all GET Endpoints are planned. PUT and DELETE Endpoints will be considered if there is interest. The following are supported:

* servers-list
* servers-show (Requires Server ID)

* applications-list
* applications-show (Requires Application ID)

* key_transactions-list
* key_transactions-show (Requires Key Transaction ID)

## Usage Examples

To get started check the examples below.

### Basic Example

```php
<?php

// Replace with real API Key - http://docs.newrelic.com/docs/apis/api-key
$api_key = 'XXXXXXXXXXXXXXXXXXXXXXX';

$newrelic = new WP_NewRelic( $api_key );

// Find the Call Type from here - https://rpm.newrelic.com/api/explore/
$newrelic->set_call_type( 'applications-list' );

$response = $newrelic->make_request();

print_r( $response );

?>
```

### Advanced Example

```php
<?php

// Replace with real API Key - http://docs.newrelic.com/docs/apis/api-key
$api_key = 'XXXXXXXXXXXXXXXXXXXXXXX';

$newrelic = new WP_NewRelic( $api_key );

// Find the Call Type from here - https://rpm.newrelic.com/api/explore/
$newrelic->set_call_type( 'applications-show' );

$app_id = 000000;

$newrelic->set_resource_id( $app_id );

// WP HTTP API Args
$args = array(
    'sslverify' => false,
);

$response = $newrelic->make_request( $args );

print_r( $response );

?>
```