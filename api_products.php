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

    $category_id = isset($_POST['category_id']) && $_POST['category_id'] != '' ? $_POST['category_id'] : '';
    $sub_category_id_1 = isset($_POST['sub_category_id_1']) && $_POST['sub_category_id_1'] != '' ? $_POST['sub_category_id_1'] : '';
    $sub_category_id_2 = isset($_POST['sub_category_id_2']) && $_POST['sub_category_id_2'] != '' ? $_POST['sub_category_id_2'] : '';
    $sub_category_id_3 = isset($_POST['sub_category_id_3']) && $_POST['sub_category_id_3'] != '' ? $_POST['sub_category_id_3'] : '';
    $sub_category_id_4 = isset($_POST['sub_category_id_4']) && $_POST['sub_category_id_4'] != '' ? $_POST['sub_category_id_4'] : '';
    $sub_category_id_5 = isset($_POST['sub_category_id_5']) && $_POST['sub_category_id_5'] != '' ? $_POST['sub_category_id_5'] : '';



    $db = getDbInstance();
    if($category_id != ''){
        $db->where('category_id', $category_id);
    }
    if($sub_category_id_1 != ''){
        $db->where('sub_category_id_1', $sub_category_id_1);
    }
    if($sub_category_id_2 != ''){
        $db->where('sub_category_id_2', $sub_category_id_2);
    }
    if($sub_category_id_3 != ''){
        $db->where('sub_category_id_3', $sub_category_id_3);
    }
    if($sub_category_id_4 != ''){
        $db->where('sub_category_id_4', $sub_category_id_4);
    }
    if($sub_category_id_5 != ''){
        $db->where('sub_category_id_5', $sub_category_id_5);
    }
    $products = $db->get('products');

    if(count($products) > 0){
        $response['status'] = 'success';
        $response['message'] = 'Data Retrieved Successfully';
        $response['data'] = $products;
    }else{
        $response['status'] = 'failure';
        $response['message'] = 'No Records Found';
        $response['data'] = '';
    }

    echo json_encode($response);

?>
