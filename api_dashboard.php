<?php

    session_start();
    header("Access-Control-Allow-Origin: *");
    //header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    //header("Content-Type: application/json; charset=UTF-8");

    require_once "config/config.php";

    // Only allow POST requests
    if(strtoupper($_SERVER['REQUEST_METHOD']) != "POST")
    {
        throw new Exception('Only POST requests are allowed');
    }

    // Make sure Content-Type is application/json
    $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    if (stripos($content_type, 'application/json') === false) {
        throw new Exception('Content-Type must be application/json');
    }

    // Read the input stream
    $body = file_get_contents('php://input');
    // Decode the JSON object
    $object = json_decode($body, true);
    $response = array();
    $dashboard_data = array();

    $total_user_count = 0;
    $total_product_count = 0;
    $products = array();

    $db = getDbInstance();
    $users = $db->get('users');

    if(count($users) > 0){
        $total_user_count = count($users);
    }

    $db = getDbInstance();
    $products = $db->get('products');

    if(count($products) > 0){
        $total_product_count = count($products);
    }

    $db = getDbInstance();
    $db->orderBy('id', 'DESC');
    $product_details = $db->get('products', 10, 'products.*');

    // echo '<pre>';print_r($product_details);echo '</pre>';exit;

    $dashboard_data['total_user_count'] = $total_user_count;
    $dashboard_data['total_product_count'] = $total_product_count;
    // $dashboard_data['products'] = $product_details;

    $response['status'] = 'success';
    $response['message'] = 'Data Retrieved Successfully';
    $response['data'] = $dashboard_data;

    echo json_encode($response);

?>
