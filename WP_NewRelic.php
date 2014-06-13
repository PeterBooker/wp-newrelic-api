<?php
/**
 * Plugin Name: New Relic API Wrapper
 * Plugin URI:  https://kebopowered.com/plugins/kebo-social/
 * Description: Handles communication between WordPress and the NewRelic API. Supports v2 of the API - https://rpm.newrelic.com/api/explore/
 * Version:     1.0
 * Author:      Peter Booker
 * Author URI:  http://peterbooker.com
 * License:     GPLv2+
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
         * Page Number
         *
         * @var string
         */
        private $page;

        /**
         * API Response Format
         *
         * @var string
         */
        private $response_format = 'json';

        /**
         * Custom WP HTTP API Args
         *
         * @var array
         */
        private $custom_http_args = array();

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
         * Gets the Page Number
         *
         * @return int
         */
        public function get_page() {

            return $this->page;

        }

        /**
         * Sets the Page Number
         *
         * @param int $page
         */
        public function set_page( $page ) {

            $this->page = $page;

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
         * Gets Custom HTTP API Args
         * See defaults: http://codex.wordpress.org/Function_Reference/wp_remote_get#Default_Usage
         *
         * @return array
         */
        public function get_http_args() {

            return $this->custom_http_args;

        }

        /**
         * Sets the Custom HTTP API Args
         * See defaults: http://codex.wordpress.org/Function_Reference/wp_remote_get#Default_Usage
         *
         * @param array $custom_http_args
         */
        public function set_http_args( $custom_http_args ) {

            $this->custom_http_args = $custom_http_args;

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
         * List the Applications for authenticated Account.
         *
         * @param string $name
         * @param string $ids
         * @param string $language
         * @return array|mixed
         */
        public function get_applications( $name = null, $ids = null, $language = null ) {

            $params = array(
                'page' => $this->page,
                'filter[name]' => $name,
                'filter[ids]' => $ids,
                'filter[language]' => $language,
            );

            $url = $this->api_url . 'applications.' . $this->response_format . '?' . http_build_query( $params, '', '&amp;' );

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Gets the Application by given Application ID.
         *
         * @param int $application_id
         * @return array|mixed
         */
        public function get_application( $application_id ) {

            $url = $this->api_url . 'applications/' . $application_id . '.' . $this->response_format;

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Lists the Metric Names for given Application ID, optionally filtered by Metric Name.
         *
         * @param int $application_id
         * @param string $name
         * @return array|mixed
         */
        public function get_application_metric_names( $application_id, $name = null ) {

            $params = array(
                'page' => $this->page,
                'name' => $name,
            );

            $url = $this->api_url . 'applications/' . $application_id . '/metrics.' . $this->response_format . '?' . http_build_query( $params, '', '&amp;' );

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Lists the Metric Data for given Application ID, optionally filtered by Names, Values and Time Period.
         *
         * @param int $application_id
         * @param array $names
         * @param array $values
         * @param string $from
         * @param string $to
         * @param bool $summarize
         * @return array|mixed
         */
        public function get_application_metric_data( $application_id, $names = array(), $values = array(), $from = null, $to = null, $summarize = null ) {

            $params = array(
                'page' => $this->page,
                'names' => $names,
                'values' => $values,
                'from' => $from,
                'to' => $to,
                'summarize' => $summarize,
            );

            $url = $this->api_url . 'applications/' . $application_id . '/metrics/data.' . $this->response_format . '?' . preg_replace( '/%5B[0-9]+?\%5D/simU', '%5B%5D', http_build_query( $params, '', '&' ) );

            $response = $this->make_request( $url, 'GET' );

            return $response;

        }

        /**
         * Lists the Key Transactions for authenticated Account.
         *
         * @param string $name
         * @param string $ids
         * @return array|mixed
         */
        public function get_key_transactions( $name = null, $ids = null ) {

            $params = array(
                'page' => $this->page,
                'filter[name]' => $name,
                'filter[ids]' => $ids,
            );

            $url = $this->api_url . 'key_transactions.' . $this->response_format . '?' . http_build_query( $params, '', '&amp;' );

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Gets the Key Transaction by given Transaction ID.
         *
         * @param int $transaction_id
         * @return array|mixed
         */
        public function get_key_transaction( $transaction_id ) {

            $url = $this->api_url . 'key_transactions/' . $transaction_id . '.' . $this->response_format;

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Lists all Servers for authenticated Account.
         *
         * @param string $name
         * @param string $ids
         * @return array|mixed
         */
        public function get_servers( $name = null, $ids = null ) {

            $params = array(
                'page' => $this->page,
                'filter[name]' => $name,
                'filter[ids]' => $ids,
            );

            $url = $this->api_url . 'servers.' . $this->response_format . '?' . http_build_query( $params, '', '&amp;' );

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Gets the Server by given Server ID.
         *
         * @param int $server_id
         * @return array|mixed
         */
        public function get_server( $server_id ) {

            $url = $this->api_url . 'servers/' . $server_id . '.' . $this->response_format;

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Lists the Metric Names for given Server ID, optionally filtered by Metric Name.
         *
         * @param int $server_id
         * @param string $name
         * @return array|mixed
         */
        public function get_server_metric_names( $server_id, $name = null ) {

            $params = array(
                'page' => $this->page,
                'name' => $name,
            );

            $url = $this->api_url . 'servers/' . $server_id . '/metrics.' . $this->response_format . '?' . http_build_query( $params, '', '&amp;' );

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Lists the Metric Data for given Server ID, optionally filtered by Names, Values and Time Period.
         *
         * @param int $server_id
         * @param array $names
         * @param array $values
         * @param string $from
         * @param string $to
         * @param bool $summarize
         * @return array|mixed
         */
        public function get_server_metric_data( $server_id, $names = null, $values = null, $from = null, $to = null, $summarize = null ) {

            $params = array(
                'page' => $this->page,
                'names' => $names,
                'values' => $values,
                'from' => $from,
                'to' => $to,
                'summarize' => $summarize,
            );

            $url = $this->api_url . 'servers/' . $server_id . '/metrics/data.' . $this->response_format . '?' . preg_replace( '/%5B[0-9]+?\%5D/simU', '%5B%5D', http_build_query( $params, '', '&amp;' ) );

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Make the HTTP Request
         *
         * @param string $url
         * @param string $method
         * @return array|mixed
         */
        public function make_request( $url, $method = 'GET' ) {

            $default_args = array(
                'method' => $method,
                'timeout' => 5,
                'httpversion' => '1.1',
                'headers' => array(
                    'x-api-key' => $this->api_key,
                ),
                'body' => null,
            );

            $args = wp_parse_args( $this->custom_http_args, $default_args );

            $response = wp_remote_request( $url, $args );

            /*
             * Check for HTTP API Error
             */
            if ( is_wp_error( $response ) ) {

                return $response->errors;

            } else {

                $status = absint( wp_remote_retrieve_response_code( $response ) );

                if ( 200 == $status ) {

                    return ( 'json' === $this->response_format ) ? json_decode( $response['body'] ) : $response['body'] ;

                } else {

                    return $response;

                }

            }

        }

    }

}