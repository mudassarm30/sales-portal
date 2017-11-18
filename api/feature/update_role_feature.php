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
		if(isset($_POST["role_id"]) && isset($_POST["feature_element_id"])){
			
			$role_id = $_POST["role_id"];
			$feature_element_id = $_POST["feature_element_id"];
			$operation = $_POST["operation"];
			$response = "";
			$existing_id = -1;
			
			$url = DB_API_BASE_URL . "/role_has_feature_element?_view=json&_filter=" . urlencode("role_id==".$role_id."&&"."feature_element_id==".$feature_element_id);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);

			if(intval($response->{"restify"}->{"rowCount"}) > 0){
				$rows = $response->{"restify"}->{"rows"};
				$existing_id = intval($rows[0]->{"values"}->{"id"}->{"value"});
			}
				
			if(strcmp($operation, "delete") === 0){
				
				if($existing_id !== -1){
					$url = DB_API_BASE_URL . "/role_has_feature_element/".$existing_id;
					$response = HTTPRequester::HTTPDelete($url);
				}
			}
			else{
				
				if($existing_id === -1){
					$url = DB_API_BASE_URL . "/role_has_feature_element";
					$data = json_encode(array(	"role_id"			 => $role_id,
												"feature_element_id" => $feature_element_id)
					);		
					$response = HTTPRequester::HTTPPost($url, array("_data" => $data));
				}
			}
			
			echo $response;
		}
		else
			throwError("Post data is not supplied", 400);
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}