<?php
/**
 * NewRelic API
 *
 * Handles communication between WordPress and the NewRelic API
 * Supports v2 of the API - https://rpm.newrelic.com/api/explore/
 *
 * @version 1.0
 */

/**
 * Basic Usage Example
 *
 * // Replace with real API Key - http://docs.newrelic.com/docs/apis/api-key
 * $api_key = 'XXXXXXXXXXXXXXXXXXXXXXX';
 *
 * $newrelic = new WP_NewRelic( $api_key );
 *
 * // Find the Call Type from here - https://rpm.newrelic.com/api/explore/
 * $newrelic->set_call_type( 'applications-list' );
 *
 * $response = $newrelic->make_request();
 *
 * print_r()
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_NewRelic' ) ) {

    class WP_NewRelic {

        /**
         * NewRelic API URL
         *
         * @var string
         */
        private $api_url = 'https://api.newrelic.com/v2/';

        /**
         * NewRelic API Key
         * http://docs.newrelic.com/docs/apis/api-key
         *
         * @var string
         */
        private $api_key;

        /**
         * NewRelic Resource ID (Account, Application, Transaction, etc)
         *
         * @var int
         */
        private $account_id;

        /**
         * NewRelic API Call Type
         *
         * @var string
         */
        private $call_type;

        /**
         * API Response Format
         *
         * @var string
         */
        private $response_format = 'json';

        /**
         * Gets the API URL
         *
         * @return string
         */
        public function get_api_url() {

            return $this->api_url;

        }

        /**
         * Sets the API URL
         *
         * @param string $api_url
         */
        public function set_api_url( $api_url ) {

            $this->api_url = $api_url;

        }

        /**
         * Gets the Resource ID
         *
         * @return int
         */
        public function get_resource_id() {

            return $this->resource_id;

        }

        /**
         * Sets the Resource ID
         *
         * @param int $resource_id
         */
        public function set_resource_id( $resource_id ) {

            $this->resource_id = absint( $resource_id );

        }

        /**
         * Gets the Call Type
         *
         * @return string
         */
        public function get_call_type() {

            return $this->call_type;

        }

        /**
         * Sets the Call Type
         *
         * @param string $call_type
         */
        public function set_call_type( $call_type ) {

            $this->call_type = $call_type;

        }

        /**
         * Gets the Response Format set
         *
         * @return string
         */
        public function get_response_format() {

            return $this->response_format;

        }

        /**
         * Sets the Response Format
         *
         * @param string $response_format
         */
        public function set_response_format( $response_format ) {

            $this->response_format = $response_format;

        }

        /**
         * Constructor
         *
         * @param string $api_key
         */
        public function __construct( $api_key ) {

            $this->api_key = $api_key;

        }

        /**
         * Builds the URL
         *
         * @return string
         */
        public function build_url() {

            switch ( $this->call_type ) {

                // Application Endpoints
                case 'applications-list':
                    $url_args = 'applications';
                    break;
                case 'applications-show':
                    $url_args = 'applications/' . $this->resource_id;
                    break;
                case 'applications-metric_names':
                    $url_args = 'applications/' . $this->resource_id . '/metrics';
                    break;
                case 'applications-metric_names':
                    $url_args = 'applications/' . $this->resource_id . '/metrics/data';
                    break;

                // Server Endpoints
                case 'servers-list':
                    $url_args = 'servers';
                    break;
                case 'servers-show':
                    $url_args = 'servers/' . $this->resource_id;
                    break;
                case 'servers-metric_names':
                    $url_args = 'servers/' . $this->resource_id . '/metrics';
                    break;
                case 'servers-metric_names':
                    $url_args = 'servers/' . $this->resource_id . '/metrics/data';
                    break;

                // Key Transaction Endpoints
                case 'key_transactions-list':
                    $url_args = 'key_transactions';
                    break;
                case 'key_transactions-show':
                    $url_args = 'key_transactions/' . $this->resource_id;
                    break;

                // Default - Not Found
                default:
                    return false;

            }

            return $this->api_url . $url_args . '.' . $this->response_format;

        }

        /**
         * Make the HTTP Request
         *
         * @param null $custom_args
         * @return array|mixed
         */
        public function make_request( $custom_args = null ) {

            $url = $this->build_url();

            if ( false === $url ) {
                return 'Error: Call Type not found.';
            }

            $default_args = array(
                'method' => 'GET',
                'timeout' => 0.1,
                'httpversion' => '1.0',
                'headers' => array(
                    'x-api-key' => $this->api_key,
                ),
                'body' => null,
                'sslverify' => true
            );

            $args = wp_parse_args( $custom_args, $default_args );

            $response = wp_remote_request( $url, $args );

            /*
             * Check for HTTP API Error
             */
            if ( is_wp_error( $response ) ) {

                return $response->errors;

            } else {

                // Decode JSON if needed
                return ( 'json' === $this->response_format ) ? json_decode( $response['body'] ) : $response['body'] ;

            }

        }

    }

}