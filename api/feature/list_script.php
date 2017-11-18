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

		$table = "feature_element";
		
		$primaryKey = "id";
		
		$columns = array(
			array( 'db' => 'id', 'dt' => 0 ),
			array( 'db' => 'name',  'dt' => 1 ),
			array(
				'db'        => 'createdon',
				'dt'        => 2,
				'formatter' => function( $d, $row ) {
					$date = explode(" ", $d);
					return $date;
				}
			)
		);
		
		listEntities($table, $primaryKey, $columns);
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}