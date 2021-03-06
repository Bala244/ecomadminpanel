<?php

/**
 * Function to generate random string.
 */
function randomString($n) {

	$generated_string = "";

	$domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

	$len = strlen($domain);

	// Loop to create random string
	for ($i = 0; $i < $n; $i++) {
		// Generate a random index to pick characters
		$index = rand(0, $len - 1);

		// Concatenating the character
		// in resultant string
		$generated_string = $generated_string . $domain[$index];
	}

	return $generated_string;
}

/**
 *
 */
function getSecureRandomToken() {
	$token = bin2hex(openssl_random_pseudo_bytes(16));
	return $token;
}

/**
 * Clear Auth Cookie
 */
function clearAuthCookie() {

	unset($_COOKIE['series_id']);
	unset($_COOKIE['remember_token']);
	setcookie('series_id', null, -1, '/');
	setcookie('remember_token', null, -1, '/');
}
/**
 *
 */
function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function sanitize_input($input_data)
{
	$sanitized_input = array();
	foreach($input_data as $key => $value)
	{
		$value = trim($value);
		$value = strip_tags($value);
		$value = htmlspecialchars($value);

		$sanitized_input[$key] = $value;
	}

	return $sanitized_input;
}

function sanitize_input_variable($input_data)
{

	$input_data = trim($input_data);
	$input_data = strip_tags($input_data);
	$input_data = htmlspecialchars($input_data);

	return $input_data;
}


function checkskucode($sku_code){
	$db = getDbInstance();
	$db->where('sku_code', $sku_code);
	$product_details = $db->get('products');

	if(count($product_details) > 0){
		$response = 'exists';
		return $response;
	}else{
		$response = 'not_exists';
		return $response;
	}
}


function checkskucodeupdate($sku_code, $product_id){
	$db = getDbInstance();
	$db->where('sku_code', $sku_code);
	$product_details = $db->get('products');

	if(count($product_details) > 0){
		// echo $product_details[0]['id'].' / '.$product_id;exit;
		if($product_details[0]['id'] != $product_id){
			$response = 'exists';
			return $response;
		}else{
			$response = 'not_exists';
			return $response;
		}
	}else{
		$response = 'not_exists';
		return $response;
	}
}


function paginationLinks($current_page, $total_pages, $base_url) {

	if ($total_pages <= 1) {
		return false;
	}

	$html = '';

	if (!empty($_GET)) {
		// We must unset $_GET[page] if previously built by http_build_query function
		unset($_GET['page']);
		// To keep the query sting parameters intact while navigating to next/prev page,
		$http_query = "?" . http_build_query($_GET);
	} else {
		$http_query = "?";
	}

	$html = '<ul class="pagination text-center">';

	if ($current_page == 1) {

		$html .= '<li class="page-item disabled"><a class="page-link">First</a></li>';
	} else {
		$html .= '<li class="page-item"><a class="page-link" href="' . $base_url . $http_query . '&page=1">First</a></li>';
	}

	// Show pagination links

	//var i = (Number(data.page) > 5 ? Number(data.page) - 4 : 1);

	if ($current_page > 5) {
		$i = $current_page - 4;
	} else {
		$i = 1;
	}

	for (; $i <= ($current_page + 4) && ($i <= $total_pages); $i++) {
		($current_page == $i) ? $li_class = ' active' : $li_class = '';

		$link = $base_url . $http_query;

		$html = $html . '<li class="page-item' . $li_class . '"><a class="page-link" href="' . $link . '&page=' . $i . '">' . $i . '</a></li>';

		if ($i == $current_page + 4 && $i < $total_pages) {

			$html = $html . '<li class="page-item disabled"><a class="page-link">...</a></li>';

		}

	}

	if ($current_page == $total_pages) {
		$html .= '<li class="page-item disabled"><a class="page-link">Last</a></li>';
	} else {

		$html .= '<li class="page-item"><a class="page-link" href="' . $base_url . $http_query . '&page=' . $total_pages . '">Last</a></li>';
	}

	$html = $html . '</ul>';

	return $html;
}

?>
