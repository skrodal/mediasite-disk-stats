<?php 

// 
$postData = json_decode(file_get_contents('php://input'), true);
// Missing/wrong access token from POST
if( !$postData['token'] || $postData['token'] !== $access_token) {
	http_response_code(401);
	exit ( json_encode(array("status" => false, "message" => "Unauthorized")) );
}
// No org content POSTed
if( !$postData['orgs'] || empty($postData['orgs']) ) {
	http_response_code(400);
	exit ( json_encode(array("status" => false, "message" => "No Content")) );
}

// All good, fetch the config and proceed

$CONFIG = json_decode(file_get_contents("/var/www/etc/mediasite-disk-stats/config.js"), true);
//
$db_host =  		$CONFIG['db_host'];
$db_name = 			$CONFIG['db_name'];
$db_table_name = 	$CONFIG['db_table_name'];
$db_user = 			$CONFIG['db_user'];
$db_pass = 			$CONFIG['db_pass'];
$access_token = $CONFIG['access_token'];

// Connect to DB
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_errno) {
	http_response_code(503);
	exit ( json_encode(array("status" => false, "message" => "Service Unavailable (DB connection failed)")) );
}

// Loop each org and save storage in db
foreach ($postData['orgs'] as $org => $size) {
	$org = $mysqli->real_escape_string($org);
	$size = round(intval($size)/1024/1024);	// Bytes to MiB
	//
	$sql = "INSERT INTO $db_table_name (org, storage_mib) VALUES ('$org', $size)";
	// Exit on error
	if (!$result = $mysqli->query($sql)) {
		http_response_code(500);
		exit ( json_encode(array("status" => false, "message" => "Internal Server Error (DB INSERT failed):" )) ); //. $mysqli->error
	}
}

// All good!
http_response_code(201);
exit ( json_encode(array("status" => true, "message" => "Created")) );