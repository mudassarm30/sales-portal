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
		
		if(isset($_POST["role_ids"])){
			
			$user_id  = $_POST["user_id"];
			$role_ids = json_decode($_POST["role_ids"]);
			
			$url = DB_API_BASE_URL . "/user_has_role?_view=json&_filter=" . urlencode("user_id==".$user_id);
			
			$response = HTTPRequester::HTTPGet($url, array());
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) > 0){
				
				$rows = $response->{"restify"}->{"rows"};
				foreach($rows as $index => $data){
					$id = $data->{"values"}->{"id"}->{"value"};
					
					$url = DB_API_BASE_URL . "/user_has_role/".$id;
			
					$response = HTTPRequester::HTTPDelete($url);
				}
			}
			
			foreach($role_ids as $index => $role_id){
				
				$data = json_encode(array(	"role_id" => $role_id,
											"user_id" => $user_id
										  )
				);
				
				$url = DB_API_BASE_URL . "/user_has_role";
				
				$response = HTTPRequester::HTTPPost($url, array("_data" => $data));
			}
			echo "";
		}
		else{
			throwError("Post data is not supplied", 400);
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}