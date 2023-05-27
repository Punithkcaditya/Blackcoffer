<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();
require_once "functions/db.php";


if(isset($_POST['selectedOptions'])){
	
// Assuming you have an array of selected topics in PHP
	$selectedTopics = $_POST['selectedOptions'];

	// Create a placeholder for the topic conditions
	$topicConditions = '';
	
	// Generate the topic conditions dynamically
	foreach ($selectedTopics as $topic) {
	$topicConditions .= "'" . $topic . "',";
	}

	// Remove the trailing comma from the topic conditions
	$topicConditions = rtrim($topicConditions, ',');
	
	// Modify the SQL query to include the topic conditions
	$sqlQuery = "SELECT id, topic, likelihood
	FROM excel_data
	WHERE topic IS NOT NULL";

	// Add the topic conditions if any are selected
	if (!empty($selectedTopics)) {
	$sqlQuery .= " AND likelihood IN ($topicConditions)";
	}

	$sqlQuery .= " GROUP BY topic
	ORDER BY id DESC LIMIT 20";

	$result = mysqli_query($connection,$sqlQuery);
	$data = array();
	foreach ($result as $row) {
		$data[] = $row;
	}
	mysqli_close($connection);
	echo json_encode($data);

}elseif(isset($_POST['selectedOptionsnew'])){
	$selectedTopics = $_POST['selectedOptionsnew'];

	// Create a placeholder for the topic conditions
	$topicConditions = '';
	
	// Generate the topic conditions dynamically
	foreach ($selectedTopics as $topic) {
	$topicConditions .= "'" . $topic . "',";
	}

	// Remove the trailing comma from the topic conditions
	$topicConditions = rtrim($topicConditions, ',');
	
	// Modify the SQL query to include the topic conditions
	$sqlQuery = "SELECT id, relevance, region
	FROM excel_data
	WHERE region IS NOT NULL";

	// Add the topic conditions if any are selected
	if (!empty($selectedTopics)) {
	$sqlQuery .= " AND relevance IN ($topicConditions)";
	}

	$sqlQuery .= " GROUP BY region
	ORDER BY id DESC LIMIT 20";

	$result = mysqli_query($connection,$sqlQuery);
	$data = array();
	foreach ($result as $row) {
		$data[] = $row;
	}
	mysqli_close($connection);
	echo json_encode($data);
}

else{
	$sqlQuery = "SELECT id, topic, likelihood  FROM excel_data WHERE topic IS NOT NULL GROUP BY topic
	ORDER BY id DESC LIMIT 20";
	$result = mysqli_query($connection,$sqlQuery);
	$data = array();
	foreach ($result as $row) {
		$data[] = $row;
	}
	mysqli_close($connection);
	echo json_encode($data);
}


?>