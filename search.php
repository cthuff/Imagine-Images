<?php
include "../inc/dbinfo.inc";
require_once 'vendor/autoload.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="myicon.png" />

  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
  
  <title>Imagine Images</title>
  
  <!-- CSS -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  
  <!-- Compiled and minified JavaScript -->
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="js/init.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/materialize.min.js"></script>
  
  
  <script src="https://apis.google.com/js/platform.js?onload=init" async defer></script>
  <meta name="google-signin-client_id" content="773465469592-70tepenvk2lc7sbhs1d1k5i98k0gdp09.apps.googleusercontent.com">
</head>
<body>
  <div id="newUser" style="display:none"></div>
  <nav class="light-blue" style="line-height: 0px"role="navigation">
    <div class="nav-wrapper" >
      <a id="logo-container" href='<?php echo $_SESSION["homeURL"];?>' class="brand-logo amber-text text-accent-2 center hide-on-small-and-down" style="padding-top:30px;">Imagine Images</a>
      <ul id="nav-mobile" class="right">
        <li><div class="right g-signin2 hide-on-small-and-down" style ="display:none;" data-onsuccess="onSignIn"></div></li\>
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
      <h4 class="header center amber-text text-accent-2 hide-on-med-and-up" style="padding:0px; padding-bottom:15px;">Imagine Images</h4>
      <div class="row">
        <div id="search1" class="col s5">
	  <select id="cat_selector">
	    <option value="" selected disabled>Choose your option</option>
	    <?php
	      $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	      // Check connection
	      if (!$conn) {
   	           die("Connection failed: " . mysqli_connect_error());
	      }
	      $sql = "SELECT DISTINCT category FROM ImageCategories;";
	      $results = $conn->query($sql);
	      $counter = 0;
	      if ($results->num_rows > 0) {
	          while($row = $results->fetch_assoc()) {
	              echo "<option value=" . $counter . ">" . $row['category'] ."</option>";
                      $counter++;
	          }
	      } else {
	          echo "No images have been uploaded yet";
	      }
	    ?>
	  </select>
	  <label>Category 1</label>
	</div>
	<div id="search2" class="col s5">
	  <select>
	    <option value="" selected disabled>Choose your option</option>
	    <?php
              $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
              // Check connection
              if (!$conn) {
                   die("Connection failed: " . mysqli_connect_error());
              }
              $sql = "SELECT * FROM UploadedImages;";
              $results = $conn->query($sql);
              $counter = 0;
              if ($results->num_rows > 0) {
                  while($row = $results->fetch_assoc()) {
                      echo "<option value=" . $counter . ">" . $row['image_name'] ."</option>";
                      $counter++;
                  }
              } else {
                  echo "No images have been uploaded yet";
              }
            ?>
	  </select>
	  <label>Category 2</label>
	</div>
	<button class="btn waves-effect waves-dark amber s2" onclick="search()"> Search 
          <i class="material-icons right">search</i>
        </button>
      </div>
    </div>
  </div>
  <div id="viewimages">
       
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
  
  <script>
  function search(){
  var cats = document.getElementById("cat_selector");
  var selected_cat = cats.options[cats.selectedIndex].text;
  
//  $.ajax({
  //      type: "GET",
    //    url: "images.php/",
      //  dataType: "html",
        //success: function(data){
         //   $('#viewimages').html(data);
      //  }
     // });
 // }
$.ajax({
      type: "GET",
	data: 'category=' + selected_cat,
	url: "image_search.php/",
        dataType: "html",
        success: function(data){
            $('#viewimages').html(data);
        }
      });
  }
  </script>
</body>
</html>
