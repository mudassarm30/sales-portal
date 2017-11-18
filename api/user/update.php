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
	
	if(checkFeatureElement(FE_Manage_Users)){
		$user = $_SESSION["user"];
		$admin_user_id = $user->{"id"}->{"value"};
									
		if(isset($_POST["firstname"])){
			
			$user_id = $_POST["user_id"];
			$url = DB_API_BASE_URL . "/user?_view=json&_filter=" . urlencode("id==".$user_id);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) === 0){
				throwError("User does not exist", 404);
			}
			
			$rows = $response->{"restify"}->{"rows"};
			$data = $rows[0];
			
			$email = $data->{"values"}->{"email"}->{"value"};
			
			$url = DB_API_BASE_URL . "/user/" . $user_id;
						
			$data = json_encode(array(	"firstname" => $_POST["firstname"],
										"lastname" => $_POST["lastname"],
										"status" => $_POST["status"],
										"paymentday" => $_POST["paymentday"],
										"autopay" => $_POST["autopay"]));
									
			$response = \Httpful\Request::put($url)->sendsJson()
												->addHeader("Accept", "application/json")
												->addHeader('Content-Type', 'x-www-form-urlencoded')
												->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
												->body("_data=".$data)->send();	
										
			$url = ENTERIS_API_BASE_URL . "/user/auth";
			
			$state = (strcmp($_POST["status"], "ACTIVE") == 0) ? "TRUE" : "FALSE";
			$data = array(  "email" => $email,
							"apiKey" => ENTERIS_KEY,
							"state" => $state
						 );
							
			$response = \Httpful\Request::post($url)->sendsJson()
												->addHeader("Accept", "application/json")
												->addHeader('Content-Type', 'application/json')
												->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
												->body(json_encode($data))->send();
			
			echo $response;
		}
		else{
			throwError("Post data is not supplied", 400);
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}