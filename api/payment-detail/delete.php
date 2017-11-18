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
	
		if(isset($_POST["id"])){
			
			$id = $_POST["id"];
			$url = DB_API_BASE_URL . "/payment_method_detail/".$id;
			
			$response = HTTPRequester::HTTPDelete($url);
		}
		else
			throwError("Post data is not supplied", 400);
		
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}