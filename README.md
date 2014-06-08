# WP NewRelic

WP NewRelic handles communication between WordPress and the NewRelic API v2. It can be added to your theme/plugin or the /mu-plugins/ folder. It is designed to allow easy fetching of data from NewRelic to use and/or display on your WordPress site.

You can see more information about NewRelic's v2 API here: [https://rpm.newrelic.com/api/explore](https://rpm.newrelic.com/api/explore)

## Authentication

An API key is used for authentication. You can find instructions on obtaining your API key [here](https://docs.newrelic.com/docs/apis/api-key).

## Supported Endpoints

Not all Endpoints are supported yet, but all GET Endpoints are planned. PUT and DELETE Endpoints will be considered if there is interest. The following are supported:

* get_applications() - *List the Applications for authenticated Account.*

* get_application( $application_id ) - *Gets the Application by given Application ID.*

* get_application_metric_names( $application_id ) - *Lists the Metric Names for given Application ID, optionally filtered by Metric Name.*

* get_application_metric_data( $application_id ) - *Lists the Metric Data for given Application ID, optionally filtered by Names, Values and Time Period.*

* get_key_transactions() - *Lists the Key Transactions for authenticated Account.*

* get_key_transaction( $transaction_id ) - *Gets the Key Transaction by given Transaction ID.*

* get_servers() - *Lists all Servers for authenticated Account.*

* get_server( $server_id ) - *Gets the Server by given Server ID.*

* get_server_metric_names( $server_id ) - *Lists the Metric Names for given Server ID, optionally filtered by Metric Name.*

* get_server_metric_data( $server_id ) - *Lists the Metric Data for given Server ID, optionally filtered by Names, Values and Time Period.*

## Usage Examples

To get started check the examples below.

### Basic Example

This basic example shows you how to fetch the details of an Application.

```php
<?php

// Replace with your API Key - http://docs.newrelic.com/docs/apis/api-key
$api_key = 'XXXXXXXXXXXXXXXXXXXXXXX';

$newrelic = new WP_NewRelic( $api_key );

$application_id = 'XXXXXXXXX';

$response = $newrelic->get_application( $application_id );

echo '<pre>';
print_r( $response );
echo '</pre>';

?>
```

### Advanced Example

This advanced example shows you how to fetch Metric Values for a particular Application. You can filter by Metric Names and Values as well as a Time Period.

```php
<?php

// Replace with real API Key - http://docs.newrelic.com/docs/apis/api-key
$api_key = 'XXXXXXXXXXXXXXXXXXXXXXX';

$newrelic = new WP_NewRelic( $api_key );

$application_id = 'XXXXXXXXX';
        
$names = array( 'External/all' );
        
$values = array( 'call_count', 'average_response_time' );
        
$now = date( 'Y-m-d H:i:s' );
        
$from = new DateTime( $now );
$from->modify( '-6 hours' );
        
$to = new DateTime( $now );
        
$response = $newrelic->get_application_metric_data( $application_id, $names, $values, $from, $to, true );

echo '<pre>';
print_r( $response );
echo '</pre>';

?>
```