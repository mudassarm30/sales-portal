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
	
	if(checkFeatureElement(FE_Update_Profile)){
		
		$user = $_SESSION["user"];
		$user_id = $user->{"id"}->{"value"};
									
		if(isset($_POST["firstname"])){
			
			$url = DB_API_BASE_URL . "/user?_view=json&_filter=" . urlencode("id==".$user_id);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) === 0){
				throwError("User does not exist", 404);
			}
			
			$url = DB_API_BASE_URL . "/user/" . $user_id;
						
			$data = json_encode(array(	"firstname" => $_POST["firstname"],
										"lastname" => $_POST["lastname"]));
									
			$response = \Httpful\Request::put($url)->sendsJson()
												->addHeader("Accept", "application/json")
												->addHeader('Content-Type', 'x-www-form-urlencoded')
												->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
												->body("_data=".$data)->send();	
												
			$url = DB_API_BASE_URL . "/address?_view=json&_filter=" . urlencode("user_id==".$user_id);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
										
			if(intval($response->{"restify"}->{"rowCount"}) > 0){
				
				$response = $response->{"restify"}->{"rows"};
				$data = $response[0];
				
				$address_id = $data->{"values"}->{"id"}->{"value"};
				
				$url = DB_API_BASE_URL . "/address/" . $address_id;
						
				$data = json_encode(array(	"address1" => $_POST["address1"],
										"address2" => $_POST["address2"],
										"zipcode" => $_POST["zipcode"],
										"city" => $_POST["city"],
										"state" => $_POST["state"],
										"country" => $_POST["country"],
										"user_id" => $user_id));
										
				$response = \Httpful\Request::put($url)->sendsJson()
												->addHeader("Accept", "application/json")
												->addHeader('Content-Type', 'x-www-form-urlencoded')
												->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
												->body("_data=".$data)->send();	
			}
			else{
				$url = DB_API_BASE_URL . "/address";
						
				$data = json_encode(array(	"address1" => $_POST["address1"],
										"address2" => $_POST["address2"],
										"zipcode" => $_POST["zipcode"],
										"city" => $_POST["city"],
										"state" => $_POST["state"],
										"country" => $_POST["country"],
										"type" => "1",
										"user_id" => $user_id,
										"createdon" => getTodayDate()));
										
				$response = HTTPRequester::HTTPPost($url, array("_data" => $data));
			}
			
			echo $response;
		}
		else{
			throwError("Post data is not supplied", 400);
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}