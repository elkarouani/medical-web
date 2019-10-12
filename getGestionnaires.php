<?php 

	require_once 'includes/functions/dao.php';
	
	$sql    = 'select * from gestionaire';
	$gestionnaire = getDatas($sql, []);
	
	echo json_encode($gestionnaire);
 
 ?>