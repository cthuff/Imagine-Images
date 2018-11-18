<!--Created by Craig Huff
11/14/18
CS 174 Final Project - Imagine Images
-->
<?php
include "../inc/dbinfo.inc";
require_once 'vendor/autoload.php';
session_start();

$_SESSION["homeURL"] = "/";
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
  <script src="https://apis.google.com/js/platform.js"></script>
  
  <!-- CSS -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css" type="text/css" media="screen,projection"/> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
 
  <!-- Compiled and minified JavaScript -->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/init.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/materialize.min.js"></script>

  <style>
    .parallax1 { 
    /* The image used */
    background-image: url("parallax.jpeg");

    /* Set a specific height */
    height: 675px; 

    /* Create the parallax scrolling effect */
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    }
    
    @media only screen and (max-device-width: 970px) {
    .parallax1 {
        background-attachment: scroll;
	height: 300px;
    }
}
  </style>
</head>
<body>
  <header>
  <nav class="light-blue" style="line-height: 0px"role="navigation">
    <ul id="nav-mobile" class="right">
        <li><div class="right g-signin2 hide-on-small-and-down" style ="padding-top:14px; padding-right:30px;"data-onsuccess="onSignIn"></div></li>
        <li><div class="right g-signin2 hide-on-med-and-up" style ="padding-top:10px; padding-right:10px"data-onsuccess="onSignIn"></div></li>
    </ul>
    <ul>
        <li><i class="large material-icons left hide-on-small-and-down" style="padding-left:30px;">camera_roll</i></li>
        <li><i class="large material-icons left hide-on-med-and-up" style="padding-left:10px;">camera_roll</i></li>
    </ul>
    </div>
  </nav>
 </header>
<div class="section no-pad-bot" id="index-banner">
    <div class="container">
        <h1 class="header center amber-text text-accent-2" style="margin:10px; padding-top:7px;">Imagine Images</h1>
        <div class="row center">
            <h5 class="header col s12 light light-blue-text ">Upload / Sell / Purchase</h5>
	    <h6 class="header col s12 light light-blue-text ">It's that simple </h6>
        </div>
        <br>
    </div>
        <div class="parallax1"></div>
</div>

<div class="container">
    <div class="section">

        <!--   Icon Section   -->
        <div class="row">
            <div class="col s12 m4">
                <div class="icon-block">
                    <h2 class="center amber-text text-accent-2"><i class="material-icons">cloud_upload</i></h2>
                    <h5 class="center">Upload Your Images</h5>

                    <p class="light">Upload any images! We'll automatically suggest categories for the file  uploaded and store it to be accessed from a secure account at any time.</p>
                </div>
            </div>

            <div class="col s12 m4">
                <div class="icon-block">
                    <h2 class="center amber-text text-accent-2"><i class="material-icons">attach_money</i></h2>
                    <h5 class="center">Sell Your Images</h5>

                    <p class="light">After an image is uploaded, there is the  ability to list the photo for sale or simply keep it in a secure profile to view privatly.</p>
                </div>
            </div>

            <div class="col s12 m4">
                <div class="icon-block">
                    <h2 class="center amber-text text-accent-2"><i class="material-icons">file_download</i></h2>
                    <h5 class="center">Search For Images</h5>

                    <p class="light">Using achine learning from Amazon's Rekognition, there is an extensive amount of categories of photos for you to search.
		    The artist sets the price for the photo, and you can buy any available images. </p>
                </div>
            </div>
        </div>

    </div>
    <br><br>
</div>

<footer class="page-footer amber lighten-1">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="black-text">About Imagine Images</h5>
                <p class="light flow-text grey-text text-darken-4">Imagine Images began as a project exploring server side development, and turned into much more than that.
		  <a href="https://craighuff.com">Craig Huff</a> is a student at San Jose State University studying computer science</p>
            </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright ">
        <div class="container ">
            <p>Photo: <i>Greetings!</i> Taken by <a class="light-blue-text" href="https://craighuff.com/skills/photos" target="_blank">Craig Huff</a> in Asbury Park, New Jersey </p>
            Made with <a class="light-blue-text" href="https://craighuff.com"> <3 </a> in San Jose, CA © 2018
        </div>
    </div>
</footer>
 
  <script>
  function onSignIn(googleUser) {
    var profile = googleUser.getBasicProfile();
    console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
    console.log('Name: ' + profile.getName());
    console.log('Image URL: ' + profile.getImageUrl());
    console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.

    var id_token = googleUser.getAuthResponse().id_token;
    <?php $_SESSION["homeURL"] = "/dashboard.php";?>  
    window.location.replace('/dashboard.php?id_token=' + id_token);
    
  }
  </script>
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
