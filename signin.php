<?php
require_once 'vendor/autoload.php';

// Get $id_token via HTTPS POST.
$CLIENT_ID = "773465469592-70tepenvk2lc7sbhs1d1k5i98k0gdp09.apps.googleusercontent.com";
$client = new Google_Client(['client_id' => $CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
$payload = $client->verifyIdToken($id_token);
if ($payload) {
  $userid = $payload['sub'];
  echo $userid;
  // If request specified a G Suite domain:
  //$domain = $payload['hd'];
} else {
  // Invalid ID token
  echo "Invalid token";
}
?>