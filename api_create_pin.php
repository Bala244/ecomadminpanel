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

    $user_id = isset($object['user_id']) && $object['user_id'] != '' ? $object['user_id'] : '';
    $pin_no = isset($object['pin_no']) && $object['pin_no'] != '' ? $object['pin_no'] : '';

    if($user_id != '' && $pin_no != ''){
        $data = array();
        $data['pin_no'] = $pin_no;

        $db = getDbInstance();
        $db->where('id', $user_id);
        if($db->update('users', $data)){
            $response['status'] = 'success';
            $response['message'] = 'Data Updated Successfully';
        }else{
            $response['status'] = 'failure';
            $response['message'] = 'Data not Updated. Please Try Again';
        }
    }else{
        $response['status'] = 'failure';
        $response['message'] = 'User ID or Pin No is empty';
    }

    echo json_encode($response);

?>
