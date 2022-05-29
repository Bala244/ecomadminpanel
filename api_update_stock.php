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

    date_default_timezone_set('Asia/Kolkata');
    $updated_at = date('Y-m-d H:i:s');

    $user_id = isset($object['user_id']) && $object['user_id'] != '' ? $object['user_id'] : '';
    $product_id = isset($object['product_id']) && $object['product_id'] != '' ? $object['product_id'] : '';
    $quantity = isset($object['quantity']) && $object['quantity'] != '' ? $object['quantity'] : '';

    if($product_id != '' && $quantity != '' && $user_id != ''){
        $db = getDbInstance();
        $db->where('id', $product_id);
        $product_details = $db->get('products');
        if(count($product_details) > 0){
            $data = array();
            $data['quantity'] = $quantity;
            $data['updated_at'] = $updated_at;
            $data['updated_by'] = $user_id;

            $db = getDbInstance();
            $db->where('id', $product_id);
            if($db->update('products', $data)){
                $response['status'] = 'success';
                $response['message'] = 'Stock Updated Successfully';
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Stock not Updated. Please Try Again';
            }
        }else{
            $response['status'] = 'failure';
            $response['message'] = 'Product Not Found. Invalid Input.';
        }
    }else{
        $response['status'] = 'failure';
        $response['message'] = 'Product ID or Quantity or User ID is empty. Please Check Input';
    }

    echo json_encode($response);

?>
