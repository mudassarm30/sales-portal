<?php

	session_start();
	
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	
	function getUser(){
		
		if(!isset($_SESSION["loggedIn"]) || ($_SESSION["loggedIn"] !== true)){
			return null;
		}
			
		$user = $_SESSION["user"];
		$user_id = $user->{"id"}->{"value"};
		
		$url = DB_API_BASE_URL . "/user?_view=json&_filter=" . urlencode("id==".$user_id);
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		$profile = Array();
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			$data = $rows[0];
			$profile["firstname"] = $data->{"values"}->{"firstname"}->{"value"};
			$profile["lastname"] = $data->{"values"}->{"lastname"}->{"value"};
		}
		else
			return null;
		
		$url = DB_API_BASE_URL . "/address?_view=json&_filter=" . urlencode("user_id==".$user_id);
		$response = HTTPRequester::HTTPGet($url, array());	
		$response = json_decode($response);
									
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$response = $response->{"restify"}->{"rows"};
			$data = $response[0];
			
			$profile["address1"] = $data->{"values"}->{"address1"}->{"value"};
			$profile["address2"] = $data->{"values"}->{"address2"}->{"value"};
			$profile["zipcode"] = $data->{"values"}->{"zipcode"}->{"value"};
			$profile["city"] = $data->{"values"}->{"city"}->{"value"};
			$profile["state"] = $data->{"values"}->{"state"}->{"value"};
			$profile["country"] = $data->{"values"}->{"country"}->{"value"};
		}

		return $profile;
	}