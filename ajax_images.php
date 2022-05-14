<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    date_default_timezone_set('Asia/Kolkata');
    $currdate = date('Y-m-d H:i:s');

    require_once "config/config.php";
    require_once "inc/auth_validate.php";
	$get_id = filter_input(INPUT_GET, 'id');


    $db = getDbInstance();
    $db->where('product_id', $get_id);
    $datas = $db->get('product_images');
    
    $resonce = [];
    $i=0;
    $j=1;
    foreach($datas as $data){
        $resonce[$i]['id'] = $data['id'];
        $resonce[$i]['src'] = $data['filepath'];

        $i++;
        $j++;
    }

    $res = json_encode($resonce);
    echo $res
?>
