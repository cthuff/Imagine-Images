<?php

include "../inc/dbinfo.inc";
require_once 'vendor/autoload.php';
session_start();

//Google Sign-In
$id_token = $_GET["id_token"];
$CLIENT_ID = "773465469592-70tepenvk2lc7sbhs1d1k5i98k0gdp09.apps.googleusercontent.com";
$client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$payload = $client->verifyIdToken($id_token);
if ($payload) {
  $userid = $payload['sub'];
  $email = $payload['email'];
  $name = $payload['name'];
  
//Upload User to Database
  $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT id FROM Users WHERE sub = '$userid';";
    $results = mysqli_query($conn, $sql);
    $row = $results->fetch_assoc();
    
    if($row) {
    	$sql = "UPDATE Users SET token='$id_token' WHERE email='$email' AND sub='$userid'; ";
	mysqli_query($conn, $sql);	
    } else {
        $sql = "INSERT INTO Users (name, email, token , sub) values ('$name', '$email', '$id_token', $userid); ";
        mysqli_query($conn, $sql);

 	$sql = "SELECT id FROM Users WHERE sub = '$userid';";
	$results = mysqli_query($conn, $sql);  
        $row = $results->fetch_assoc();
    }
    $_SESSION['sql_id'] = $row['id'];
    mysqli_close($conn);
} else {
  // Invalid ID token
  echo "<a>Invalid token</a>";
}
?>