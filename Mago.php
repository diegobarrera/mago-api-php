<?php

class Mago {
    
    public $apikey;
    public $base;
    public $root = 'partner.mercadoni.com';
    public $version = 'v1.0-api';

    public function __construct() {
        if (!function_exists('curl_init')) throw new Exception('The curl extension for PHP is required.');
        $this->base = $this->root.'/'.$this->version;
    }
   
    public function setToken($apikey, $environment) {
        $this->apikey = $apikey;    
    }

    public function getMe() {
        $__vars = array(
            'token'=>$this->apikey
        );
        return $this->post($__vars, "/me");
    }

    public function getLocations() {
        $__vars = array(
            'token' => $this->apikey
        );
        return $this->post($__vars, "/list_locations");
    }

    public function createLocation($location) {

        $__vars = $location;
        $__vars['token'] = $this->apikey;

        return $this->post($__vars, "/post_locations");
    }

    public function editLocation($patch) {

        $__vars = $patch;
        $__vars['token'] = $this->apikey;
        
        return $this->post($__vars, "/put_locations");
    }

    public function createOrder($order) {

        $__vars = $order;
        $__vars['token'] = $this->apikey;
        
        return $this->post($__vars, "/post_orders");

    }

    public function getOrderInfo($order_id) {

        $__vars = array(
            'token' => $this->apikey,
            '_id' => $order_id
        );

        return $this->post($__vars, "/get_order");
    }

     public function getOrderInfoByInternal($internal_id) {

        $__vars = array(
            'token' => $this->apikey,
            'internal' => $internal_id
        );

        return $this->post($__vars, "/get_order_by_internal");
    }

    function post($__vars, $__url) {

        $__postfields = http_build_query($__vars);
        $__ch = curl_init( $this->base . $__url );
        curl_setopt( $__ch, CURLOPT_POST, 1);
        curl_setopt( $__ch, CURLOPT_POSTFIELDS, $__postfields);
        curl_setopt( $__ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $__ch, CURLOPT_HEADER, 0);
        curl_setopt( $__ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec( $__ch );

        $info = curl_getinfo($__ch);

        if(curl_error($__ch)) {
            throw new Exception("API call to $__url failed: " . curl_error($__ch));
        }

        $result = json_decode($response, true);
    
        return $result;
    }
}
