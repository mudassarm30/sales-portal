<?php
	session_start();
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(checkFeatureElement(FE_Define_Roles)){
		if(isset($_POST["role_id"])){
			
			$role_id = $_POST["role_id"];
			$url = DB_API_BASE_URL . "/role?_view=json&_filter=" . urlencode("id==".$role_id);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) === 0){
				throwError("Role does not exist", 404);
			}
			
			$url = DB_API_BASE_URL . "/role?_view=json&_filter=" . urlencode("name==".$_POST["name"]);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) > 0){
				throwError("Role with the given name already exists", 400);
			}
			
			$params = array();
			
			if(strcmp(trim($_POST["name"]), "") !== 0)
				$params = array("name" => trim($_POST["name"]));
			
			$data = json_encode($params);
			
			$url = DB_API_BASE_URL . "/role/" . $role_id;
					
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