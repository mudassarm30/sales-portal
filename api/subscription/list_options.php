<?php

	session_start();

	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(checkFeatureElement(FE_Define_Subscriptions)){
		
		$subscription_id = -1;
		
		$url = DB_API_BASE_URL . "/user_has_subscription";
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			
			foreach($rows as $index => $data){
				$record_id = $data->{"values"}->{"id"}->{"value"};
				$subscription_id = $data->{"values"}->{"subscription_id"}->{"value"};
				break;
			}
		}
		
		$url = DB_API_BASE_URL . "/subscription";
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		echo "<option value=''></option>";
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			
			foreach($rows as $index => $data){
			
				$id = $data->{"values"}->{"id"}->{"value"};
				$name = $data->{"values"}->{"name"}->{"value"};
				$selected = (strcmp($id, "".$subscription_id) == 0)?"selected" : "";
				
				?>
				<option value="<?=$id?>" <?=$selected?>><?=$name?></option>
				<?php
			}
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}