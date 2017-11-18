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
		$url = DB_API_BASE_URL . "/role_has_feature_element?_view=json&_filter=" . urlencode("role_id==".$_GET["role_id"]);
		
		$response = HTTPRequester::HTTPGet($url, array());	
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			$ids = Array();
			
			for($i = 0; $i < count($rows); $i++){
				$feature_element_id = $rows[$i]->{"values"}->{"feature_element_id"}->{"value"};
				array_push($ids, $feature_element_id);
			}
			
			echo json_encode($ids);
		}
		else{
			echo "[]";
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}
?>