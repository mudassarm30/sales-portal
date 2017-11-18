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
		
		$table = "history";
		
		$primaryKey = "id";
		
		$columns = array(
			array( 'db' => 'id', 'dt' => 0 ),
			array( 'db' => 'type',  'dt' => 1 ),
			array(
				'db'        => 'message',
				'dt'        => 2,
				'formatter' => function( $d, $row ) {
					$short = substr($d, 0, 80) . " ... ";
					return $short.'<input type="hidden" id="message_'.$row["id"].'" value=\''.$d.'\' />';
				}
			),
			array('db' => 'createdon', 'dt' => 3),
			array('db' => 'user_id', 'dt' => 4)
		);
		
		listEntities($table, $primaryKey, $columns);
	}
	else{
		echo OPERATION_NOT_ALLOWED;
	}