<!--Created by Craig Huff
11/14/18
CS 174 Final Project - Imagine Images
-->
<?php
include "../inc/dbinfo.inc";
require_once 'vendor/autoload.php';
session_start();
$urls = array("https://images.craighuff.com/upload.php", "https://images.craighuff.com/delete.php","https://images.craighuff.com/index.php","https://images.craighuff.com/dashboad.php");
 
if(isset($_SESSION['token']) == false){
    echo '<meta http-equiv="refresh" content="0; url=/">';
    exit(0);
}
$_SESSION["homeURL"] = "/dashboard.php";
$sql_id = $_SESSION['sql_id'];

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
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <!-- Compiled and minified JavaScript -->
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/materialize.min.js"></script>
  <script src="js/init.js"></script>

  <script src="https://apis.google.com/js/platform.js?onload=init" async defer></script>
  <meta name="google-signin-client_id" content="773465469592-70tepenvk2lc7sbhs1d1k5i98k0gdp09.apps.googleusercontent.com">
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.dropdown-trigger');
    var instances = M.Dropdown.init(elems);
  });
  </script>
</head>
<body>
<div id="newUser" style="display:none"></div>
<form id="deleteForm" name="deleteForm" method="post" action="delete.php" method="post">
    <input type="hidden" name="filename" value="">
</form>

  <nav class="light-blue" style="line-height: 0px"role="navigation">
    <div class="nav-wrapper" >
    <a id="logo-container" href='<?php echo $_SESSION["homeURL"];?>' class="brand-logo amber-text text-accent-2 center hide-on-small-and-down" style="padding-top:30px;">Imagine Images</a>
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
       	    <h4 class="header center amber-text text-accent-2 hide-on-med-and-up" style="padding:0px; padding-bottom:15px;">Imagine Images</h4>
       	    <form action="upload.php" method="post" enctype="multipart/form-data">
            <h4 class="light-blue-text" style="text-align: center; padding-bottom:15px;">Dashboard</h4>
            <h6 class="amber-text" style="style='font-size:16px; margin-bottom:5px;">Select image to upload:</h6>
	    <div class="file-field input-field">
            	 <div class="btn waves-effect waves-dark amber" width="75px">
            	      &nbsp;&nbsp;<span>File</span>&nbsp;&nbsp;
            	      <input type="file" name="fileToUpload" id="fileToUpload" required accept="image/png,image/jpg,image/jpeg" >
          	 </div>
          	 <div class="file-path-wrapper">
            	      <input class="file-path validate disabled" type="text">
          	 </div>
	    </div>
	    <div class="row" style="margin-bottom:0px;">
	    	 <div class="input-field col s10" style="padding:0px;" >
	       	      <input placeholder="Enter a new name for the image" autocomplete="off" name="newFileName" id="newFileName" type="text" class="validate" pattern="[a-zA-Z0-9]+([-_]*[a-zA-Z0-9]+)*" >
                      <label style="margin-left:-10px;"for="newFileName">Image Name</label>
		      <span class="helper-text" data-error="Invalid Characters in Name" data-success="Great Name!"></span>
               	 </div>
	    </div>
        <button class="btn waves-effect waves-dark amber" type="submit"name="submit"> Upload Image
		<i class="material-icons right">cloud_upload</i>
	</button>
	</form>
   </div>
   <div class="section">   
    <div class="row">
      <div class="col s6">
      <?php
        $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
	
        $sql = "SELECT * FROM UploadedImages WHERE user_id='$sql_id';";
        $results = $conn->query($sql);
        $image_names = array();

        if ($results->num_rows > 0) {
            echo "<h5 class='light-blue-text' style='margin-bottom:5px;'>Uploads <br></h5>";
            while($row = $results->fetch_assoc()) { 
              $image_name = $row['image_name'];
              array_push($image_names, $image_name);
              echo "<a class='amber-text' target=_blank href=uploads/$image_name>" . $image_name . "<br></a>";
            }
          }
          else {
            echo "No images have been uploaded yet";
          }	  
      ?>
      </div>
      <div class="col s6">
      <?php
        $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT * FROM PurchasedImages WHERE buyer_id=$sql_id;";
        $results = $conn->query($sql);
        
        if ($results->num_rows > 0) {
            echo "<h5 class='amber-text' style='margin-bottom:5px;'>Purchases <br></h5>";
            while($row = $results->fetch_assoc()) {
              $image_name = $row['image_name'];
              
              echo "<a target=_blank href=uploads/$image_name>" . $image_name . "<br></a>";
            }
          }
          else {
            echo "No images have been purchased yet";
          }
      ?>
      </div>
    </div>
      <br>

      <!-- Dropdown Trigger -->
      <a id="deleteButton" class='dropdown-trigger btn waves-effect waves-dark amber' action="delete.php" data-target='deleteDropdown'>Delete An Uploaded Image</a>
      <!-- Dropdown Structure -->
      <ul id='deleteDropdown' class='dropdown-content'> 
      </ul>

    </div>
    <br>
    <button class="btn waves-effect waves-dark amber" onclick="window.location='/search.php'"> Search Images
                <i class="material-icons right">search</i>
    </button>

    </div>
</div>
<br>
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

  <script>
      function deleteImage(){
        var imgName = this.innerHTML;
        document.deleteForm.filename.value = imgName;
        document.forms["deleteForm"].submit();
      }
  </script>

  <script>
  function uploadedFiles() {
    var deleteList = document.getElementById("deleteDropdown");
    var items = <?php echo json_encode($image_names); ?> ;
    var length = items.length; 

    if(length > 0){
      for (var i = 0; i < length; i++) {
        var entry = document.createElement('li');
        entry.appendChild(document.createTextNode(items[i]));
        entry.onclick = deleteImage;
        deleteList.appendChild(entry);
      } 
    } else {
      var btn = document.getElementById("deleteButton")
      btn.style.display = "none";
    }
  }

  uploadedFiles();
  </script>
  
</body>

</html>