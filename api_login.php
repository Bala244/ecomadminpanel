<?php
    header("Access-Control-Allow-Origin: *");
    //header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    header('Access-Control-Max-Age: 1000');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header("Content-Type: application/json; charset=UTF-8");

    require_once 'config/config.php';

    // Only allow POST requests
    if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
      throw new Exception('Only POST requests are allowed');
    }

    // Make sure Content-Type is application/json
    $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
    if (stripos($content_type, 'application/json') === false) {
      throw new Exception('Content-Type must be application/json');
    }

    // Read the input stream
    $body = file_get_contents("php://input");
    // Decode the JSON object
    $object = json_decode($body, true);


    // Throw an exception if decoding failed
    if (!is_array($object)) {
      throw new Exception('Failed to decode JSON object');
    }

    // Display the object

    $response = array();

    $email = $object['username'];
    $password = $object['password'];

    $query1 = "SELECT * FROM `users` WHERE `email`='".$email."' AND `is_active`=1";
    $execute1 = mysqli_query($conn, $query1);

    if(mysqli_num_rows($execute1) > 0){
        $user_details = mysqli_fetch_assoc($execute1);
        $db_password = $user_details['password'];

        if(password_verify($password, $db_password)){
            $response['status'] = 'success';
            $response['message'] = 'Login Successful';
            $response['data'] = $user_details;
        }else{
            $response['status'] = 'failure';
            $response['message'] = 'Invalid Credentials';
            $response['data'] = '';
        }
    }else{
        $response['status'] = 'failure';
        $response['message'] = 'Invalid Credentials';
        $response['data'] = '';
    }

    echo json_encode($response);exit;

?>
