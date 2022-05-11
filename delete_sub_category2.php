<?php

    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $id = $_POST['id'];

        if($id != ''){
            $db = getDbInstance();
            $db->where('id', $id);
            if($db->delete('sub_category_2')){
                $_SESSION['success'] = "Sub Category 2 Deleted Successfully";
                header("Location:sub_category_2.php");exit;
            }else{
                $_SESSION['failure'] = "Sub Category 2 Not Deleted. Please Try Again";
                header("Location:sub_category_2.php");exit;
            }
        }else{
            $_SESSION['failure'] = "Invalid Input";
            header("Location:sub_category_2.php");exit;
        }
    }

?>
