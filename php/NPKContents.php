<?php 
	include $_SERVER['DOCUMENT_ROOT'] . "/php/DatabaseManager.php"; 
	
	$soilTypes = ["nitrogen", "phosphorous", "potassium"];

	$soil = strval($_GET["soil"]);

	// Only get the last/latest values from the database
	$query = mysqli_query($connection, "SELECT " . $soil . " FROM mysql.Soil_Data ORDER BY id LIMIT 6");
	
	// Create array
	$data = array();
	
	// Fetch each resulting row from the query
	while($result_metadata = mysqli_fetch_assoc($query)){
		$data[] = $result_metadata;
	}
	
	// Format data to json
	echo json_encode($data);
?>