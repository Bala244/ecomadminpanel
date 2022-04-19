<?php

    session_start();

    if(isset($_SESSION['success']))
    {
        echo $_SESSION['success'];
        unset($_SESSION['success']);
    }

    if(isset($_SESSION['failure']))
    {
        echo $_SESSION['failure'];
        unset($_SESSION['failure']);
    }

    if(isset($_SESSION['info']))
    {
        echo $_SESSION['info'];
        unset($_SESSION['info']);
    }

?>
