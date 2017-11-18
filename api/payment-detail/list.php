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
		
		$url = DB_API_BASE_URL . "/payment_method_detail?_count=50";
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			
			foreach($rows as $index => $data){
				$date = $data->{"values"}->{"createdon"}->{"value"};
				$date = explode(" ", $date);
				?>
				<tr>
					<td><?=$data->{"values"}->{"id"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"field1"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"field2"}->{"value"}?></td>
					<td id="payment_method_id_<?=$data->{"values"}->{"id"}->{"value"}?>" data-payment_method_id="<?=$data->{"values"}->{"paymentmethod_id"}->{"outReference"}->{"values"}->{"id"}?>"><?=$data->{"values"}->{"paymentmethod_id"}->{"outReference"}->{"values"}->{"name"}?></td>
					<td><?=$date[0]?></td>
				</tr>
				<?php
			}
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}