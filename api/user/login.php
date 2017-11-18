<?php

	session_start();

	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(isset($_POST["email"]) && isset($_POST["password"])){
		
		$url = DB_API_BASE_URL . "/user?_view=json&_filter=" . urlencode("email==".$_POST["email"]);
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) === 0){
			throwError("Email does not exist", 404);
		}
		
		$pdpasshash = base64_encode($_POST["password"]);
		$dbpasshash = $response->{"restify"}->{"rows"}[0]->{"values"}->{"password"}->{"value"};
		
		if(strcmp($pdpasshash, $dbpasshash) !== 0){
			throwError("Wrong password", 400);
		}
		
		$user_id = $response->{"restify"}->{"rows"}[0]->{"values"}->{"id"}->{"value"};
		$feature_elements = getUserFeatureElements($user_id);
		
		$_SESSION["featureElements"] = $feature_elements;
		$_SESSION["loggedIn"] = true;
		$_SESSION["user"] = $response->{"restify"}->{"rows"}[0]->{"values"};
	}
	else{
		throwError("Credentials not provided", 400);
	}
	
?>