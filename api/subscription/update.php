<?php
	session_start();
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(checkFeatureElement(FE_Define_Subscriptions)){
		if(isset($_POST["subscription_id"])){
			
			$subscription_id = $_POST["subscription_id"];
			$url = DB_API_BASE_URL . "/subscription?_view=json&_filter=" . urlencode("id==".$subscription_id);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if(intval($response->{"restify"}->{"rowCount"}) === 0){
				throwError("Subscription does not exist", 404);
			}
			
			$rows = $response->{"restify"}->{"rows"};
			$data = $rows[0];
			
			$id = $data->{"values"}->{"id"}->{"value"};
			
			$url = DB_API_BASE_URL . "/subscription?_view=json&_filter=" . urlencode("name==".$_POST["name"]);
			$response = HTTPRequester::HTTPGet($url, array());	
			$response = json_decode($response);
			
			if((intval($response->{"restify"}->{"rowCount"}) > 0) && (strcmp($id, $subscription_id) != 0)){
				throwError("Subscription with the given name already exists", 400);
			}
			
			$data = json_encode(array(	"name" => $_POST["name"],
										"storage" => $_POST["storage"],
										"units" => $_POST["units"],
										"cost" => $_POST["cost"],
										"currency" => $_POST["currency"]));
			
			$url = DB_API_BASE_URL . "/subscription/" . $subscription_id;
					
			$response = \Httpful\Request::put($url)->sendsJson()
											->addHeader("Accept", "application/json")
											->addHeader('Content-Type', 'x-www-form-urlencoded')
											->addHeader('User-Agent', $_SERVER ['HTTP_USER_AGENT'])
											->body("_data=".$data)->send();	
			
			echo $response;
		}
		else{
			throwError("Post data is not supplied", 400);
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}