<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";
    $db = getDbInstance();
    $highlighters = $db->get('highlighter');

	$status = filter_input(INPUT_GET, 'status');
	$id = filter_input(INPUT_GET, 'id');

    $status = isset($status) && $status != '' ? $status : '';
    $updated_at = date('Y-m-d H:i:s');
    $updated_by = $_SESSION['user_id'];


    $query1 = "UPDATE `highlighter` SET `status` = '".$status."', `updated_at` = '".$updated_at."',
    `updated_by` = '".$updated_by."' WHERE `id` = ".$id."";

    if(mysqli_query($conn, $query1)){
    // echo $query1;exit;
        echo "success";

        $_SESSION['success'] = 'User Data Updated Successfully.';
        exit;
    }else{
        echo "fail";

        $_SESSION['failure'] = 'User Data Not Updated. Please Try Again.';
        exit;
    }
    

?>