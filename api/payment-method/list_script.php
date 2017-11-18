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
	
	if(checkFeatureElement(FE_Define_Payment_Methods)){
		
		$table = "payment_method";
		
		$primaryKey = "id";
		
		$columns = array(
			array( 'db' => 'id', 'dt' => 0 ),
			array( 'db' => 'name',  'dt' => 1 ),
			array(
				'db'        => 'createdon',
				'dt'        => 2,
				'formatter' => function( $d, $row ) {
					
					$date = $d;
					$date = explode(" ", $date);
					return $date[0];
				}
			),
			array(
				'db'        => 'fields',
				'dt'        => 3,
				'formatter' => function( $d, $row ) {
					$id = $row["id"];
					$fields = $d;
					return $date[0].'<textarea style="display: none" id="fields_'.$id.'">'.$fields.'</textarea>';
				}
			)
		);
		
		listEntities($table, $primaryKey, $columns);
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}