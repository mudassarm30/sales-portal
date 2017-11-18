<?php
	session_start();
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(checkFeatureElement(FE_Manage_Users)){
		$url = DB_API_BASE_URL . "/user";
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			
			foreach($rows as $index => $data){
				$date = $data->{"values"}->{"createdon"}->{"value"};
				$date = explode(" ", $date);
				$auto = (strcmp($data->{"values"}->{"autopay"}->{"value"}, "1") === 0)?"true":"false";
				?>
				<tr>
					<td><?=$data->{"values"}->{"id"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"email"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"firstname"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"lastname"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"status"}->{"value"}?></td>
					<td><?=$date[0]?></td>
					<td><?=$data->{"values"}->{"paymentday"}->{"value"}?></td>
					<td><?=$auto?></td>
				</tr>
				<?php
			}
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}