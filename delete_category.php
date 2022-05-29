<?php

    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $category_id = $_POST['category_id'];

        if($category_id != ''){
            $db = getDbInstance();
            $db->where('id', $category_id);
            if($db->delete('category')){
                $_SESSION['success'] = "Category Deleted Successfully";
                header("Location:categories.php");exit;
            }else{
                $_SESSION['failure'] = "Category Not Deleted. Please Try Again";
                header("Location:categories.php");exit;
            }
        }else{
            $_SESSION['failure'] = "Invalid Input";
            header("Location:categories.php");exit;
        }
    }

?>
