<?php

	session_start();

	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(!isset($_SESSION["loggedIn"]) || ($_SESSION["loggedIn"] !== true)){
		throwError("User not logged in", 400);
	}
	
	if(checkFeatureElement(FE_Update_Payment_Details)){
	
		if(!isset($_GET['id'])){
			throwError("Post data is not supplied", 400);
		}
		
		$user = $_SESSION["user"];
		$user_id = $user->{"id"}->{"value"};
		
		$id = $_GET['id'];
		$url = DB_API_BASE_URL . "/payment_method_detail?_view=json&_expand=no&_filter=" . urlencode("id==".$id."&&user_id==".$user_id);
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			$data = $rows[0];
			echo json_encode($data->{"values"}, false);
		}
	
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}