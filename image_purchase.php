<?php
include "../inc/dbinfo.inc";
session_start();

//Variables needed for purchasing image
$filename = $_POST["filename"];
$buyer_id = $_SESSION["sql_id"];
$path = "uploads/" . $filename;

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT image_id, user_id FROM UploadedImages WHERE image_name = '$filename';";
$results = $conn->query($sql);
$row = $results->fetch_assoc();

//Variables based on SQL call
$publisher_id = $row['user_id'];
$image_id = $row['image_id'];

try {
    if ($publisher_id == $buyer_id) {        
	throw new Exception('Cannot Purchase Your Own Photo', 403);
    } else {    
    $sql = "INSERT INTO PurchasedImages (image_id, publisher_id, buyer_id, image_name, filepath) values ('$image_id', '$publisher_id', '$buyer_id', '$filename', '$path');";
    mysqli_query($conn, $sql);
    }
    echo json_encode(array(
        'result' =>  "Success!",
    ));
} catch (Exception $e) {
    
    echo json_encode(array(
        'error' =>  $e->getMessage(),
    ));
}
mysqli_close($conn);
?>