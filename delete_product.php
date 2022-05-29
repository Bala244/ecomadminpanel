<?php

    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    require_once "config/config.php";
    require_once "inc/auth_validate.php";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $product_id = $_POST['product_id'];

        if($product_id != ''){
            $db = getDbInstance();
            $db->where('id', $product_id);
            if($db->delete('products')){
                $db->where('product_id', $product_id);

                if($db->delete('product_images')){
                    $_SESSION['success'] = "Product Deleted Successfully";
                    header("Location:products.php");exit;
                }else{
                    $_SESSION['failure'] = "Product Deleted. Image data not Deleted. Please Try Again";
                    header("Location:products.php");exit;
                }

            }else{
                $_SESSION['failure'] = "Product Not Deleted. Please Try Again";
                header("Location:products.php");exit;
            }
        }else{
            $_SESSION['failure'] = "Invalid Input";
            header("Location:products.php");exit;
        }
    }

?>
