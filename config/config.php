<?php

    error_reporting(E_ALL);
    ini_set('display_errors', 'Off');
    define('BASE_PATH', dirname(dirname(__FILE__)));
    define('CURRENT_PAGE', basename($_SERVER['REQUEST_URI']));

    // require_once BASE_PATH . '/lib/MysqliDb/MysqliDb.php';
    require_once BASE_PATH . '/helpers/helpers.php';

    /* ------- Local DB Configuration ----- */

    define('DB_HOST', "localhost");
    define('DB_USER', "root");
    define('DB_PASSWORD', "");
    define('DB_NAME', "packurs");

    /* ------- Live DB Configuration ----- */

    // define('DB_HOST', "localhost");
    // define('DB_USER', "packurs_packurs_admin");
    // define('DB_PASSWORD', "admin@2022");
    // define('DB_NAME', "packurs_admin");

    /**
    * Get instance of DB object
    */

    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    function getDbInstance() {
        return new MysqliDb(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }

?>
