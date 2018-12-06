<!--Created by Craig Huff
11/14/18
CS 174 Final Project - Imagine Images
-->

<?php
include "../inc/dbinfo.inc";
session_start();

if($_SESSION["homeURL"] == "/"){
echo '<meta http-equiv="refresh" content="0; url=/">';
exit(0);
} else if (!$_POST['filename']){
echo '<meta http-equiv="refresh" content="0; url=/dashboard.php">';
exit(0);
}

$filename = $_POST['filename'];
shell_exec("rm uploads/" . $filename );
shell_exec('aws s3 sync /var/www/html/uploads/ s3://rekognitiontest174/ --delete ');
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
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
  <meta name="google-signin-client_id" content="773465469592-70tepenvk2lc7sbhs1d1k5i98k0gdp09.apps.googleusercontent.com">
  <title>Imagine Images</title>
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  
  <!-- CSS -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <!-- Compiled and minified JavaScript -->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/init.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/materialize.min.js"></script>

</head>
<body>
  
  <nav class="light-blue" style="line-height: 0px"role="navigation">
    <div class="nav-wrapper">
    	 <a id="logo-container" href=<?php echo $_SESSION["homeURL"];?>  class="brand-logo amber-text text-accent-2 center hide-on-small-and-down" style="padding-top:30px;">Imagine Images</a>

    <ul id="nav-mobile" class="right">
    	<li><div class="right g-signin2 hide-on-small-and-down" style ="display:none;" data-onsuccess="onSignIn"></div></li>
        <li><div class="right btn amber hide-on-small-and-down" style="margin-top:14px; margin-right:30px;" onclick="signOut()"><span class="black-text">Sign Out</span></div></li>
        <li><div class="right btn amber hide-on-med-and-up" style ="margin-top:10px; margin-right:10px" onclick="signOut()"><span class="black-text">Sign Out</span></div></li>
    </ul>
    <ul>
        <li><i class="large material-icons left hide-on-small-and-down" style="padding-left:30px;">camera_roll</i></li>
        <li><i class="large material-icons left hide-on-med-and-up" style="padding-left:10px;">camera_roll</i></li>
    </ul>
    </div>
  </nav>
  <div class="container">
    <div class="section">

    <h3><a> <?php echo "You've removed the image:<br>" . $filename ; ?> </a></h3>
    <a class="waves-effect waves-dark amber btn-large" style="display: block; margin-left: auto; margin-right: auto;" href=<?php echo $_SESSION["homeURL"];?> >Home</a>
    </div>
    
<div class="section no-pad-bot" id="index-banner">
  <div class="container"></div>
</div>

<script>
  function onSignIn(googleUser) {

    var profile = googleUser.getBasicProfile();
    console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
    console.log('Name: ' + profile.getName());
    console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.

    var id_token = googleUser.getAuthResponse().id_token;
    }
  </script>

  <script>
  function signOut() {
    gapi.auth2.init();
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
    $( "#newUser" ).load( "logout.php", function() { });
    window.location.replace('/');
  }
  </script>

</body>

</html>