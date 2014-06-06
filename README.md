# WP NewRelic

WP NewRelic handles communication between WordPress and the NewRelic API v2. It can be added to your theme/plugin or the /mu-plugins/ folder.

## Supported Endpoints

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