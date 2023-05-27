<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
require_once "functions/db.php";

$sqlQuery = "SELECT id, likelihood, city
	FROM excel_data
	WHERE city IS NOT NULL GROUP BY city
	ORDER BY id DESC LIMIT 20";
    $result = mysqli_query($connection,$sqlQuery);
	$data = array();
	foreach ($result as $row) {
		$data[] = $row;
	}
	mysqli_close($connection);
	echo json_encode($data);






?>