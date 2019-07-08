<?php 
	include $_SERVER['DOCUMENT_ROOT'] . "/php/DatabaseManager.php"; 
	
	$query = strval($_GET["query"]);

	// Only get the last/latest values from the database
	$query = mysqli_query($connection, $query);
	// Error check
	if($query == FALSE){
		die();
	}

	// Create array
	$data = array();
	
	// Fetch each resulting row from the query
	while($result_metadata = mysqli_fetch_assoc($query)){
		$data[] = $result_metadata;
	}
	
	// Format data to json
	echo json_encode($data);
?>