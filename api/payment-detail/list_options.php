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
		$url = DB_API_BASE_URL . "/payment_method";
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			
			foreach($rows as $index => $data){
				?>
				<option data-fields="<?=$data->{"values"}->{"fields"}->{"value"}?>" value="<?=$data->{"values"}->{"id"}->{"value"}?>"><?=$data->{"values"}->{"name"}->{"value"}?></option>
				<?php
			}
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}