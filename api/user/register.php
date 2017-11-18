<?php
	session_start();
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	
	$url = DB_API_BASE_URL . "/user?_view=json&_filter=" . urlencode("email==".$_POST["email"]);
		
	$response = HTTPRequester::HTTPGet($url, array());
	$response = json_decode($response, true);
	$count = intval($response["restify"]["rowCount"]);
	
	if($count > 0){
		throwError("User email " . $_POST["email"] . " is not available", 404);
	}
	
	$data = json_encode(array(	"email" => $_POST["email"],
								"password" 	=> base64_encode($_POST["password"]),
								"firstname" => $_POST["firstname"],
								"lastname" 	=> $_POST["lastname"], 
								"status"	=> INACTIVE,
								"createdon" => getTodayDate(),
								"paymentday"=> getToday(),
								"autopay"	=> 0)
	);
	
	$url = DB_API_BASE_URL . "/user";
	
	$response = HTTPRequester::HTTPPost($url, array("_data" => $data));
	$url = ENTERIS_API_BASE_URL . "/user/register";
  
	$data = array(  "lastName" => urlencode($_POST["lastname"]),  
					"license" => "1",
					"password" => $_POST["password"],
					"role" => "0",
					"apiKey" => ENTERIS_KEY,
					"firstnName" => urlencode($_POST["firstname"]),
					"admin" => ENTERIS_ADMIN,
					"storage" => "10",
					"adminPass" => ENTERIS_PASS,
					"email" => $_POST["email"] );
	

	$response = \Httpful\Request::put($url)->sendsJson()
											->addHeader("Accept", "application/json")
											->addHeader('Content-Type', 'application/json')
											->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
											->body(json_encode($data))->send(); 	
?>