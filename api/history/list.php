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
	
	if(checkFeatureElement(FE_See_History)){
		
		$url = DB_API_BASE_URL . "/history";
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			
			foreach($rows as $index => $data){
				$date = $data->{"values"}->{"createdon"}->{"value"};
				$date = explode(" ", $date);
				$firstname = $data->{"values"}->{"user_id"}->{"outReference"}->{"values"}->{"firstname"};
				$lastname = $data->{"values"}->{"user_id"}->{"outReference"}->{"values"}->{"lastname"};
				$lastname = $data->{"values"}->{"user_id"}->{"outReference"}->{"values"}->{"lastname"};
				$email = $data->{"values"}->{"user_id"}->{"outReference"}->{"values"}->{"email"};
				$message = $data->{"values"}->{"message"}->{"value"};
				$short = substr($message, 0, 80) . " ... ";
				?>
				<tr>
					<td><?=$data->{"values"}->{"id"}->{"value"}?></td>
					<td><?=$data->{"values"}->{"type"}->{"value"}?></td>
					<td><?=$short?><input type="hidden" id="message_<?=$data->{"values"}->{"id"}->{"value"}?>" value="<?=$message?>" /></td>
					<td><?=$date[0]?></td>
					<td title="<?=$email?>" style="cursor: pointer"><?php echo $firstname . " " . $lastname?></td>
				</tr>
				<?php
			}
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}