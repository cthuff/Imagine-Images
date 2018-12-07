<?php
include "../inc/dbinfo.inc";
session_start();
$filename = $_POST["filename"];

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT user_id FROM UploadedImages WHERE image_name = '$filename';";
$results = $conn->query($sql);
$row = $results->fetch_assoc();
error_log($row["user_id"]);
try {
    if ($row['user_id'] == $_SESSION["sql_id"]) {        
	throw new Exception('Cannot Purchase Your Own Photo', 300);
    }
    echo json_encode(array(
        'result' => 'vanilla!',
    ));
} catch (Exception $e) {
    
    echo json_encode(array(
        'error' =>  $e->getMessage(),
    ));
}
?>