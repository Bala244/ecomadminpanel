<?php

    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $user_id = $_POST['user_id'];

        if($user_id != ''){
            $db = getDbInstance();
            $db->where('id', $user_id);
            if($db->delete('users')){
                $_SESSION['success'] = "User Deleted Successfully";
                header("Location:users.php");exit;
            }else{
                $_SESSION['failure'] = "User Not Deleted. Please Try Again";
                header("Location:users.php");exit;
            }
        }else{
            $_SESSION['failure'] = "Invalid Input";
            header("Location:users.php");exit;
        }
    }

?>
