<!--Created by Craig Huff
11/14/18
CS 174 Final Project - Imagine Images
-->
<?php
include "../inc/dbinfo.inc";
require_once 'vendor/autoload.php';
session_start();

if($_SESSION["homeURL"] == "/"){
echo '<meta http-equiv="refresh" content="0; url=/">';
exit(0);
}
$_SESSION['token'] = $_GET['id_token'];
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="myicon.png" />

  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="google-signin-client_id" content="773465469592-70tepenvk2lc7sbhs1d1k5i98k0gdp09.apps.googleusercontent.com">
  <title>Imagine Images</title>
  <script src="https://apis.google.com/js/platform.js"></script>
  
  <!-- CSS -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <!-- Compiled and minified JavaScript -->
  <script src="js/materialize.js"></script>
  <script src="js/materialize.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.dropdown-trigger');
    var instances = M.Dropdown.init(elems);
  });
  </script>

</head>
<body>
  
<form id="deleteForm" name="deleteForm" method="post" action="delete.php" method="post">
    <input type="hidden" name="filename" value="">
</form>

  <nav class="light-blue" style="line-height: 0px"role="navigation">
    <div class="nav-wrapper" >
    <a id="logo-container" href=<?php echo $_SESSION["homeURL"];?> class="brand-logo amber-text text-accent-2 center hide-on-small-and-down" style="padding-top:30px;">Imagine Images</a>
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
  <div class="container">
       <div class="section">
       	    <h4 class="header center amber-text text-accent-2 hide-on-med-and-up" style="padding:0px; padding-bottom:15px;">Imagine Images</h4>
       	    <form action="upload.php" method="post" enctype="multipart/form-data">
       	    <h5 class="light-blue-text">Select image to upload:</h5>
	    <div class="file-field input-field">
            	 <div class="btn waves-effect waves-dark amber" width="75px">
            	      &nbsp;&nbsp;<span>File</span>&nbsp;&nbsp;
            	      <input type="file" name="fileToUpload" id="fileToUpload" required >
          	 </div>
          	 <div class="file-path-wrapper">
            	      <input class="file-path validate disabled" type="text">
          	 </div>
	    </div>
	    <div class="row">
	    	 <div class="input-field col s10" style="padding:0px;" >
	       	      <input placeholder="Enter a new name for the image" name="newFileName" id="newFileName" type="text" class="validate" pattern="[a-zA-Z0-9]+([-_]*[a-zA-Z0-9]+)*" >
                      <label style="margin-left:-10px;"for="newFileName">Image Name</label>
		      <span class="helper-text" data-error="Invalid Characters in Name" data-success="Great Name!"></span>
               	 </div>
	    </div>
        <button class = "btn waves-effect waves-dark amber" type="submit"name="submit"> Upload Image
		<i class="material-icons right">cloud_upload</i>
	</button>
	</form>
   </div>
      
      <?php
        $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT * FROM UploadedImages;";
        $results = $conn->query($sql);
        $image_names = array();

        //$var = shell_exec('ls uploads/');
        if ($results->num_rows > 0) {
            echo "<a>The files that have been uploaded are: <br></a>";
            while($row = $results->fetch_assoc()) { 
              $image_name = $row['image_name'];
              array_push($image_names, $image_name);
              echo "<a target=_blank href=uploads/$image_name>" . $image_name . "<br></a>";
            }
          }
          else {
            echo "No images have been uploaded yet";
          }
      ?>
      <br>

      <!-- Dropdown Trigger -->
      <a id="deleteButton" class='dropdown-trigger btn waves-effect waves-dark amber' action="delete.php" data-target='deleteDropdown'>Delete An Uploaded Image</a>
      <!-- Dropdown Structure -->
      <ul id='deleteDropdown' class='dropdown-content'> 
      </ul>
    </div>
    <br>
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
    
    //THIS PAGE NEEDS TO BE REMADE AS THE DASHBOARD, NEED A SPLASH PAGE
    // if( id_token == <php $_GET['id_token']?> ){
    // alert("tokens aren't the same");
    // }
    // else {
    // window.location.replace('/dashboard.php?id_token=' + id_token);
    // }
	
    // <php
    // $CLIENT_ID = '773465469592-70tepenvk2lc7sbhs1d1k5i98k0gdp09.apps.googleusercontent.com';
    // $id_token = $_GET['id_token'];
    
    //?>
    
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
    var length = items.length; //<?php echo count($image_names); ?> ;

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