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

        $db = getDbInstance();
        $db->where('id', $user_id);
        $user = $db->get('users');

        if(count($user) > 0){
            if($user[0]['pin_no'] == $pin_no){
                $status = '';

                if($user[0]['is_active'] == 1){
                    $status = 'Active';
                }else{
                    $status = 'InActive';
                }

                $user_data = array();
                $user_data['id'] = $user[0]['id'];
                $user_data['name'] = $user[0]['name'];
                $user_data['email'] = $user[0]['email'];
                $user_data['pin_no'] = $user[0]['pin_no'];
                $user_data['admin_type'] = $user[0]['admin_type'];
                $user_data['mobile_no'] = $user[0]['mobile_no'];
                $user_data['gender'] = $user[0]['gender'];
                $user_data['address'] = $user[0]['address'];
                $user_data['profile_image'] = 'https://packurs.com/admin/'.$user[0]['profile_image'];
                $user_data['is_active'] = $status;

                $response['status'] = 'success';
                $response['message'] = 'Pin No Match';
                $response['data'] = $user_data;
            }else{
                $response['status'] = 'failure';
                $response['message'] = 'Pin No Do Not Match';
                $response['data'] = '';
            }
        }else{
            $response['status'] = 'failure';
            $response['message'] = 'User Not Found';
            $response['data'] = '';
        }
    }else{
        $response['status'] = 'failure';
        $response['message'] = 'User ID or Pin No is empty';
        $response['data'] = '';
    }

    echo json_encode($response);

?>
