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
	
	if(checkFeatureElement(FE_Define_Feature_Elements)){	
		if(isset($_POST["feature_element_id"])){
			
			$feature_element_id = $_POST["feature_element_id"];
			$url = DB_API_BASE_URL . "/feature_element?_view=json&_filter=" . urlencode("id==".$feature_element_id);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) === 0){
				throwError("Feature element does not exist", 404);
			}
			
			$url = DB_API_BASE_URL . "/feature_element?_view=json&_filter=" . urlencode("name==".$_POST["name"]);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) > 0){
				throwError("Feature element with the given name already exists", 400);
			}
			
			$data = json_encode(array("name" => $_POST["name"]));
			
			$url = DB_API_BASE_URL . "/feature_element/" . $feature_element_id;
					
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