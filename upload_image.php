<?php

include "../inc/dbinfo.inc";
require_once 'vendor/autoload.php';
session_start();


// ############################
// # THIS WORKS for categories
// ############################

//$categories = $_SESSION["cat1"];
//$purchasable = $_SESSION["purchasable"];

//error_log($categories);
    // #####################################
    // #          MySQL calls
    // #####################################
    $target_name = $_GET["name"];
    $target_file = $_GET["path"];
    print_r($_GET);
    // Create connection
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    // Check connection
    if (!$conn) {
	die("Connection failed: " . mysqli_connect_error());
    }

    // #####################################
    // #
    // # THE USER ID WILL BE CHANGED WHEN
    // # GOOGLE SIGN-IN IS FULLY IMPLEMENTED
    // #
    // #####################################

    //$sql = "SELECT id FROM Userss WHERE token = $_SESSION['id_token']";
    //$results = mysqli_query($conn, $sql);
    //$row = $results->fetch_assoc()
    //$user_id = $row['token'];
    
    $sql = "INSERT INTO UploadedImages (user_id, image_name, filepath) values (10, '$target_name', '$target_file')";
    mysqli_query($conn, $sql);
    mysqli_close($conn);

    
?>