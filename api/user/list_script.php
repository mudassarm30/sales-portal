<?php
	session_start();
	include_once __DIR__ . "/../common/constants.php";
	include_once __DIR__ . "/../common/config.php";
	include_once __DIR__ . "/../common/util.php";
	include_once __DIR__ . "/../common/http.php";
	include_once __DIR__ . "/../common/common.php";
	
	if(checkFeatureElement(FE_Manage_Users)){
		
		$table = "user";
		
		$primaryKey = "id";
		
		$columns = array(
			array( 'db' => 'id', 'dt' => 0 ),
			array( 'db' => 'email',  'dt' => 1 ),
			array( 'db' => 'firstname',  'dt' => 2 ),
			array( 'db' => 'lastname',  'dt' => 3 ),
			array( 'db' => 'status',  'dt' => 4 ),
			array(
				'db'        => 'createdon',
				'dt'        => 5,
				'formatter' => function( $d, $row ) {
					$date = explode(" ", $d);
					return $date;
				}
			),
			array( 'db' => 'paymentday',  'dt' => 6 ),
			array(
				'db'        => 'autopay',
				'dt'        => 7,
				'formatter' => function( $d, $row ) {
					$auto = (strcmp($d, "1") === 0)?"true":"false";
					return $auto;
				}
			)
		);
		
		listEntities($table, $primaryKey, $columns);
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}