<?php
require_once '/vendor/autoload.php';
include "../inc/dbinfo.inc";
session_start();

$filename = $_POST['filename'];
shell_exec("rm uploads/" . $filename );

// #####################################
// #          MySQL calls 
// #####################################

// Create connection
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select database
// $sql = "USE IMAGINEIMAGES";
// mysqli_query($conn, $sql);

// #####################################
// # THE USER ID WILL BE CHANGED WHEN 
// # GOOGLE SIGN-IN IS FULLY IMPLEMENTED
// #####################################

$sql = "DELETE FROM UploadedImages WHERE image_name = '$filename' ;";
mysqli_query($conn, $sql);
  
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>

  <link rel="icon" type="image/png" href="myicon.png" />

  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="google-signin-client_id" content="773465469592-70tepenvk2lc7sbhs1d1k5i98k0gdp09.apps.googleusercontent.com">
  <title>Imagine Images</title>
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  
  <!-- CSS -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">


</head>
<body>
  
  <nav class="light-blue lighten-1" style="line-height: 0px"role="navigation">
    <div class="nav-wrapper" ><a id="logo-container" href="/" class="brand-logo center" style ="padding-top:30px;">Imagine Images</a>

    <ul id="nav-mobile" class="right hide-on-med-and-down">
        <li><div class="right g-signin2" style ="padding-top:14px; padding-right:30px;"data-onsuccess="onSignIn"></div></li>
      </ul>
    <ul class="hide-on-med-and-down">
        <li><i class="large material-icons left" style="padding-left:30px;">camera_roll</i></li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <div class="section">

    <h3><a> <?php echo "You've removed the image:<br>" . $filename ; ?> </a></h3>
<a class="waves-effect waves-light btn-large" style="display: block; margin-left: auto; margin-right: auto;" href="/">Home</a>
    </div>
    
<div class="section no-pad-bot" id="index-banner">
  <div class="container"></div>
</div>


<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

<script>
function signOut() {
  var auth2 = gapi.auth2.getAuthInstance();
  auth2.signOut().then(function () {
    console.log('User signed out.');
  });
}
</script>

</body>

</html>