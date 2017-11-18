<?php
	session_start();
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(checkFeatureElement(FE_Search_Capability)){
		if(!isset($_SESSION["loggedIn"]) || ($_SESSION["loggedIn"] !== true)){
			throwError("User not logged in", 400);
		}
		
		if(!isset($_GET["isFile"])){
			throwError("Search parameters not specified", 400);
		}
		
		$user 		= $_SESSION["user"];
		$user_id 	= $user->{"id"}->{"value"};
		$email 		= $user->{"email"}->{"value"};
		$password 	= base64_decode($user->{"password"}->{"value"});
		
		$pattern 	= urlencode($_GET["pattern"]);
		$isFile 	= $_GET["isFile"];
		$isMedia 	= $_GET["isMedia"];
		$isArticle 	= $_GET["isArticle"];
		$isDeep 	= $_GET["isDeep"];
		$page 		= $_GET["page"];
		$count 		= $_GET["count"];
		
		
		$url = ENTERIS_API_BASE_URL . "/content/solr/search?pattern=".$pattern."&type=0&isPublic=false&isFile=".$isFile."&isMedia=".$isMedia."&isDeep=".$isDeep."&isArticle=".$isArticle."&page=".$page."&count=".$count."&email=".urlencode($email)."&password=".$password;
		
		$response = file_get_contents($url);
		
		echo $response;
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}