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

    // $category_id = isset($object['category_id']) && $object['category_id'] != '' ? $object['category_id'] : '';
    // $sub_category_id_1 = isset($object['sub_category_id_1']) && $object['sub_category_id_1'] != '' ? $object['sub_category_id_1'] : '';
    // $sub_category_id_2 = isset($object['sub_category_id_2']) && $object['sub_category_id_2'] != '' ? $object['sub_category_id_2'] : '';
    $sub_category_id_3 = isset($object['sub_category_id_3']) && $object['sub_category_id_3'] != '' ? $object['sub_category_id_3'] : '';

    if($sub_category_id_3 != ''){
        $response = array();

        $db = getDbInstance();
        $db->where('sub_category_id_3', $sub_category_id_3);
        $sub_category_4 = $db->get('sub_category_4');

        if(count($sub_category_4) > 0){
            $response['status'] = 'success';
            $response['message'] = 'Data Retrieved Successfully';
            $response['data'] = $sub_category_4;
        }else{
            $response['status'] = 'failure';
            $response['message'] = 'No Records Found';
            $response['data'] = '';
        }
    }else{
        $response['status'] = 'failure';
        $response['message'] = 'Sub Category 3 ID Missing';
        $response['data'] = '';
    }

    echo json_encode($response);

?>
