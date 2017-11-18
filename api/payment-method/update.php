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
	
	if(checkFeatureElement(FE_Define_Payment_Methods)){
		if(isset($_POST["payment_method_id"])){
			
			$payment_method_id = $_POST["payment_method_id"];
			$url = DB_API_BASE_URL . "/payment_method?_view=json&_filter=" . urlencode("id==".$payment_method_id);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) === 0){
				throwError("Payment method does not exist", 404);
			}
			
			$url = DB_API_BASE_URL . "/payment_method?_view=json&_filter=" . urlencode("name==".$_POST["name"]."&&"."id!=".$payment_method_id);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) > 0){
				throwError("Payment method with the given name already exists", 400);
			}
			
			$data = json_encode(array("name" => $_POST["name"], "fields" => $_POST["fields"]));
			
			$url = DB_API_BASE_URL . "/payment_method/" . $payment_method_id;
					
			$response = \Httpful\Request::put($url)->sendsJson()
											->addHeader("Accept", "application/json")
											->addHeader('Content-Type', 'x-www-form-urlencoded')
											->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
											->body("_data=".$data)->send();	
			
			echo $response;
		}
		else{
			throwError("Post data is not supplied", 400);
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}