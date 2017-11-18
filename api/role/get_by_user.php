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
		
	if(checkFeatureElement(FE_Define_Roles)){		
		$user = $_SESSION["user"];
		$admin_user_id = $user->{"id"}->{"value"};
		
		$roles = Array();
		if(isset($_GET["user_id"])){		
			$url = DB_API_BASE_URL . "/user_has_role?_view=json&_filter=" . urlencode("user_id==".$_GET["user_id"]);
			
			$response = HTTPRequester::HTTPGet($url, array());
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) > 0){
				
				$rows = $response->{"restify"}->{"rows"};
				foreach($rows as $index => $data){
					array_push($roles, $data->{"values"}->{"role_id"}->{"value"});
				}
			}
		}
		echo json_encode($roles);
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}