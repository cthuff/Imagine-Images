<?php

// #####################################
//#		INTITIALIZATION        #
// #####################################
include "../inc/dbinfo.inc";
require_once 'vendor/autoload.php';
session_start();
$sql_id = $_SESSION['sql_id'];

$categories = $_SESSION["categories"];
$cat_index = $_POST["cats"];
$size = count($cat_index);

//$purchasable = $_SESSION["purchasable"];
$target_name = $_POST["name"];
$target_file = $_POST["path"];

// #####################################
// #          MySQL calls
// #####################################    
// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}

// #####################################
// #	    Insert Image to DB         #
// #####################################
//Upload the images to the Image Database
$sql = "INSERT INTO UploadedImages (user_id, image_name, filepath) values ('$sql_id', '$target_name', '$target_file')";
mysqli_query($conn, $sql);

// #####################################
// #	Categories Addes to Images     #
// #####################################     
$sql = "SELECT image_id FROM UploadedImages WHERE image_name='$target_name';";
$result = mysqli_query($conn, $sql);
$row = $result->fetch_assoc();
$image_id = $row['image_id'];

if($size > 0){
   for($i = 0; $i < $size; $i++){
        $temp_cat = $categories[$i]['Name'];
	$sql = "INSERT INTO ImageCategories (image_id, category) values ('$image_id', '$temp_cat')";
        mysqli_query($conn, $sql);
   }
} else {
  $sql = "INSERT INTO ImageCategories (id, category) values ('$image_id', 'uncategorized')";
  mysqli_query($conn, $sql);
}

mysqli_close($conn);    
?>