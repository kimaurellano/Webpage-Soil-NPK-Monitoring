<?php 
	$connection = mysqli_connect("localhost", "cdtekk", "D5g5FwnLCXvDiSWY", "mysql");
        
	// Check connection
	if (!$connection) {
		echo $connection -> error;
		exit();
	}
?>
