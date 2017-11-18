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
		if(isset($_POST["name"])){
		
			$url = DB_API_BASE_URL . "/payment_method?_view=json&_filter=" . urlencode("name==".$_POST["name"]);
				
			$response = HTTPRequester::HTTPGet($url, array());
			$response = json_decode($response, true);
			$count = intval($response["restify"]["rowCount"]);
			
			if($count > 0){
				throwError("Payment method '" . $_POST["name"] . "' already exists", 404);
			}
			
			$data = json_encode(array(	"name" => $_POST["name"],
										"fields" => $_POST["fields"],
										"createdon" => getTodayDate()
									  )
			);
			
			$url = DB_API_BASE_URL . "/payment_method";
			
			$response = HTTPRequester::HTTPPost($url, array("_data" => $data));
			
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