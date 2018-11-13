<?php
include "/vendor/autoload.php";
include "../inc/dbinfo.inc";
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="icon" type="image/png" href="myicon.png" />

  <meta charset="utf-8" />
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

  <nav class="light-blue lighten-1" style="line-height: 0px"role="navigation">
    <div class="nav-wrapper" ><a id="logo-container" href="/" class="brand-logo center" style ="padding-top:30px;">Imagine Images</a>

    <ul id="nav-mobile" class="right">
        <li><div class="right g-signin2" style ="padding-top:14px; padding-right:30px;"data-onsuccess="onSignIn"></div></li>
      </ul>
    <ul>
        <li><i class="large material-icons left" style="padding-left:30px;">camera_roll</i></li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <div class="section">
      <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <div class="file-field input-field">
          <div class="btn">
            <span>File</span>
            <input type="file" name="fileToUpload" id="fileToUpload">
          </div>
          <div class="file-path-wrapper">
            <input class="file-path validate" type="text">
          </div>
        </div>
          <input class = "btn" type="submit" value="Upload Image" name="submit">
      </form>

      <br>
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
      <a id="deleteButton" class='dropdown-trigger btn' action="delete.php" data-target='deleteDropdown'>Delete An Uploaded Image</a>
      <!-- Dropdown Structure -->
      <ul id='deleteDropdown' class='dropdown-content'> 
      </ul>
    </div>
  </div>

  <div class="section no-pad-bot" id="index-banner">
    <div class="container"></div>
  </div>
 
  <script>
  function onSignIn(googleUser) {

    var profile = googleUser.getBasicProfile();
    console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
    console.log('Name: ' + profile.getName());
    console.log('Image URL: ' + profile.getImageUrl());
    console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.

    var id_token = googleUser.getAuthResponse().id_token;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/signin.php');
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      console.log('Signed in as: ' + xhr.responseText);
    };
    xhr.send('idtoken=' + id_token);
  }
  </script>

  <script>
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
    require_once '/path/to/your-project/vendor/autoload.php';
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