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
	
		$user = $_SESSION["user"];
		$user_id = $user->{"id"}->{"value"};
		
		if(isset($_POST["payment_method"]) && isset($_POST["field1"]) && isset($_POST["id"])){
		
			$payment_method = $_POST["payment_method"];
			$id 	= isset($_POST["id"]) ? $_POST["id"] : "";
			$field1 = isset($_POST["field1"]) ? $_POST["field1"] : "";
			$field2 = isset($_POST["field2"]) ? $_POST["field2"] : "";
			$field3 = isset($_POST["field3"]) ? $_POST["field3"] : "";
			$field4 = isset($_POST["field4"]) ? $_POST["field4"] : "";
			$field5 = isset($_POST["field5"]) ? $_POST["field5"] : "";
			$field6 = isset($_POST["field6"]) ? $_POST["field6"] : "";
			$field7 = isset($_POST["field7"]) ? $_POST["field7"] : "";
			$field8 = isset($_POST["field8"]) ? $_POST["field8"] : "";
			$field9 = isset($_POST["field9"]) ? $_POST["field9"] : "";
			$field10 = isset($_POST["field10"]) ? $_POST["field10"] : "";
			
			$data = json_encode(array(	"field1" => $field1,
										"field2" => $field2,
										"field3" => $field3,
										"field4" => $field4,
										"field5" => $field5,
										"field6" => $field6,
										"field7" => $field7,
										"field8" => $field8,
										"field9" => $field9,
										"field10" => $field10,
										"paymentmethod_id" => $payment_method,
										"createdon" => getTodayDate(),
										"user_id" => $user_id
									  )
			);
			
			$url = DB_API_BASE_URL . "/payment_method_detail/".$id;
			
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
?>