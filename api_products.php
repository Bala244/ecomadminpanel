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

    $name = isset($object['name']) && $object['name'] != '' ? $object['name'] : '';
    $sku_code = isset($object['sku_code']) && $object['sku_code'] != '' ? $object['sku_code'] : '';
    $category_id = isset($object['category_id']) && $object['category_id'] != '' ? $object['category_id'] : '';
    $sub_category_id_1 = isset($object['sub_category_id_1']) && $object['sub_category_id_1'] != '' ? $object['sub_category_id_1'] : '';
    $sub_category_id_2 = isset($object['sub_category_id_2']) && $object['sub_category_id_2'] != '' ? $object['sub_category_id_2'] : '';
    $sub_category_id_3 = isset($object['sub_category_id_3']) && $object['sub_category_id_3'] != '' ? $object['sub_category_id_3'] : '';
    $sub_category_id_4 = isset($object['sub_category_id_4']) && $object['sub_category_id_4'] != '' ? $object['sub_category_id_4'] : '';
    $sub_category_id_5 = isset($object['sub_category_id_5']) && $object['sub_category_id_5'] != '' ? $object['sub_category_id_5'] : '';
    $page_limit = isset($object['page_limit']) && $object['page_limit'] != '' ? $object['page_limit'] : 20;
    $page = isset($object['page']) && $object['page'] != '' ? $object['page'] : 1;


    $db = getDbInstance();
    $db->pageLimit = $page_limit;
    if($name != ''){
        $db->where('name', '%'.$name.'%', 'LIKE');
    }
    if($sku_code != ''){
        $db->where('sku_code', $sku_code);
    }
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
    $db->orderBy('id', 'desc');
    $products = $db->arraybuilder()->paginate('products', $page, 'products.*');

    if(count($products) > 0){

        $product_list = array();
        $i = 0;

        foreach($products as $product){
            $category_details = array();
            $sub_category_1_details = array();
            $sub_category_2_details = array();
            $sub_category_3_details = array();
            $sub_category_4_details = array();
            $sub_category_5_details = array();
            $user_details = array();
            $product_images = array();

            $category_name = 'NA';
            $sub_category_1_name = 'NA';
            $sub_category_2_name = 'NA';
            $sub_category_3_name = 'NA';
            $sub_category_4_name = 'NA';
            $sub_category_5_name = 'NA';
            $user_name = 'NA';

            $product_id = $product['id'];
            $category_id = $product['category_id'];
            $sub_category_id_1 = $product['sub_category_id_1'];
            $sub_category_id_2 = $product['sub_category_id_2'];
            $sub_category_id_3 = $product['sub_category_id_3'];
            $sub_category_id_4 = $product['sub_category_id_4'];
            $sub_category_id_5 = $product['sub_category_id_5'];

            if($data['status'] == 1){
                $status = 'Active';
            }else{
                $status = 'InActive';
            }


            $db = getDbInstance();
            $db->where('product_id', $product_id);
            $product_image_details = $db->get('product_images');

            if(count($product_image_details) > 0){
                foreach($product_image_details as $data){
                    // $product_images[] = "http://packurs.com/admin/".$data['filepath'];
                    $product_images[] = $data['filepath'];
                }
            }
            // echo '<pre>';print_r($product_images);echo '</pre>';exit;
            $db = getDbInstance();
            $db->where('id', $category_id);
            $category_details = $db->get('category');

            if(count($category_details) > 0){
                $category_name = $category_details[0]['name'];
            }

            $db = getDbInstance();
            $db->where('id', $sub_category_id_1);
            $sub_category_1_details = $db->get('sub_category_1');

            if(count($sub_category_1_details) > 0){
                $sub_category_1_name = $sub_category_1_details[0]['name'];
            }

            $db = getDbInstance();
            $db->where('id', $sub_category_id_2);
            $sub_category_2_details = $db->get('sub_category_2');

            if(count($sub_category_2_details) > 0){
                $sub_category_2_name = $sub_category_2_details[0]['name'];
            }

            $db = getDbInstance();
            $db->where('id', $sub_category_id_3);
            $sub_category_3_details = $db->get('sub_category_3');

            if(count($sub_category_3_details) > 0){
                $sub_category_3_name = $sub_category_3_details[0]['name'];
            }

            $db = getDbInstance();
            $db->where('id', $sub_category_id_4);
            $sub_category_4_details = $db->get('sub_category_4');

            if(count($sub_category_4_details) > 0){
                $sub_category_4_name = $sub_category_4_details[0]['name'];
            }

            $db = getDbInstance();
            $db->where('id', $sub_category_id_5);
            $sub_category_5_details = $db->get('sub_category_5');

            if(count($sub_category_5_details) > 0){
                $sub_category_5_name = $sub_category_5_details[0]['name'];
            }

            $db = getDbInstance();
            $db->where('id', $data['created_by']);
            $user_details = $db->get('sub_category_5');

            if(count($user_details) > 0){
                $user_name = $user_details[0]['name'];
            }

            $product_list[$i]['id'] = $product['id'];
            $product_list[$i]['name'] = $product['name'];
            $product_list[$i]['description'] = $product['description'];
            $product_list[$i]['category_name'] = $product['name'];
            $product_list[$i]['sub_category_1_name'] = $sub_category_1_name;
            $product_list[$i]['sub_category_2_name'] = $sub_category_2_name;
            $product_list[$i]['sub_category_3_name'] = $sub_category_3_name;
            $product_list[$i]['sub_category_4_name'] = $sub_category_4_name;
            $product_list[$i]['sub_category_5_name'] = $sub_category_5_name;
            $product_list[$i]['sku_code'] = $product['sku_code'];
            $product_list[$i]['quantity'] = $product['quantity'];
            $product_list[$i]['is_retail'] = $product['is_retail'];
            $product_list[$i]['is_whole_sale'] = $product['is_whole_sale'];
            $product_list[$i]['is_ecommerce'] = $product['is_ecommerce'];
            $product_list[$i]['retail_price'] = $product['retail_price'];
            $product_list[$i]['whole_sale_price'] = $product['whole_sale_price'];
            $product_list[$i]['ecommerce_price'] = $product['ecommerce_price'];
            $product_list[$i]['status'] = $status;
            $product_list[$i]['created_at'] = date_format(date_create($product['created_at']), 'd-m-Y H:i:s');
            $product_list[$i]['created_by'] = $user_name;
            $product_list[$i]['images'] = $product_images;

            $i++;
        }

        $response['status'] = 'success';
        $response['message'] = 'Data Retrieved Successfully';
        $response['data'] = $product_list;
    }else{
        $response['status'] = 'failure';
        $response['message'] = 'No Records Found';
        $response['data'] = '';
    }

    echo json_encode($response);

?>
