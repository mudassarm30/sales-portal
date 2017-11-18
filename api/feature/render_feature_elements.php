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
	
	if(checkFeatureElement(FE_Define_Feature_Elements)){	
		$url = DB_API_BASE_URL . "/feature_element";
		
		$response = HTTPRequester::HTTPGet($url, array());
		$response = json_decode($response);
		
		if(intval($response->{"restify"}->{"rowCount"}) > 0){
			
			$rows = $response->{"restify"}->{"rows"};
			
			for($i = 0; $i < count($rows); $i++){
				$id = $rows[$i]->{"values"}->{"id"}->{"value"};
				$name = $rows[$i]->{"values"}->{"name"}->{"value"};
				?>
					<input type="checkbox" class="feature_element" id="fe_<?=$id?>" value="<?=$id?>" onchange="featureToggled(<?=$id?>)" disabled>&nbsp;<?=$name?></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php
			}
		}
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}