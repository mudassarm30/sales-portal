<?php
	session_start();
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(checkFeatureElement(FE_Define_Subscriptions)){
		$url = DB_API_BASE_URL . "/subscription";
		
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
					<td><?=$data->{"values"}->{"name"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"storage"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"units"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"cost"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"currency"}->{"value"}?></td>
					<td><?=$date[0]?></td>
				</tr>
				<?php
			}
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}