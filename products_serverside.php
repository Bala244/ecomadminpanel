<?php

 include_once "config/config.php";

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'products';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'id', 'dt' => 0 ),
    array( 'db' => 'name',  'dt' => 1 ),
    array( 'db' => 'sku_code',  'dt' => 2 ),
    array( 'db' => 'quantity',  'dt' => 3 ),
    array(
        'db'        => 'status',
        'dt'        => 4,
        'formatter' => function( $d, $row ) {
            return ($d == 1)?'Active':'Inactive';
        }
    ) ,
    array("defaultContent: <button id='myBtn' type='button' class='btn'>Manage</button>", "dt" => 5)
);


$searchFilter = array();
if(!empty($_GET['name'])){
    $searchFilter['search'] = array(
        'name' => $_GET['name'],
        'sku_code' => $_GET['name']
    );
}

if(!empty($_GET['sub_category_1']) || !empty($_GET['sub_category_2']) || !empty($_GET['sub_category_3']) || !empty($_GET['sub_category_4']) || !empty($_GET['sub_category_5']) || !empty($_GET['sub_category_6']) || !empty($_GET['filter_order_val']) || !empty($_GET['filter_order'])){
    $searchFilter['filter'] = array(
        'category_id' => $_GET['sub_category_1'],
        'sub_category_id_1' => $_GET['sub_category_2'],
        'sub_category_id_2' => $_GET['sub_category_3'],
        'sub_category_id_3' => $_GET['sub_category_4'],
        'sub_category_id_4' => $_GET['sub_category_5'],
        'sub_category_id_5' => $_GET['sub_category_6']
    );
}

// SQL server connection information
$sql_details = array(
    'user' => DB_USER,
    'pass' => DB_PASSWORD,
    'db'   => DB_NAME,
    'host' => DB_HOST
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( 'ssp.class.php' );

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $searchFilter )
);
