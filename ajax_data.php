<?php
    session_start();

    header("X-XSS-Protection: 1; mode=block");
    header("X-Frame-Options: SAMEORIGIN");

    date_default_timezone_set('Asia/Kolkata');
    $currdate = date('Y-m-d H:i:s');

    require_once "config/config.php";
    require_once "inc/auth_validate.php";
	$get_id = filter_input(INPUT_GET, 'id');
	$cate_id = filter_input(INPUT_GET, 'cate_id');

    if ($cate_id == 'sub_category_2') {
        $db = getDbInstance();
        $db->where('sub_category_id_1', $get_id);
        $datas = $db->get($cate_id);

        $option = '<option>Choose a Value</option>';

        foreach ($datas as $data) {
            $option .= "<option value=".$data['id'].">".$data['name']."</option>";
        }
    }elseif ($cate_id == 'sub_category_3') {
        $db = getDbInstance();
        $db->where('sub_category_id_2', $get_id);
        $datas = $db->get($cate_id);

        $option = '<option>Choose a Value</option>';

        foreach ($datas as $data) {
            $option .= "<option value=".$data['id'].">".$data['name']."</option>";
        }
    }else{
        $db = getDbInstance();
        $db->where('category_id', $get_id);
        $datas = $db->get($cate_id);

        $option = '<option>Choose a Value</option>';

        foreach ($datas as $data) {
        	$option .= "<option value=".$data['id'].">".$data['name']."</option>";
        }
    }
    echo $option;
?>
