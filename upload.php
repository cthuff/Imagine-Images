<!--Created by Craig Huff
11/14/18
CS 174 Final Project - Imagine Images
-->
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


<?php
include "../inc/dbinfo.inc";

$target_name = preg_replace('/\s+/', '', basename($_FILES["fileToUpload"]["name"]));  
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
echo $_POST["newFileName"];
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));


  if ($_FILES["fileToUpload"]["error"] > 0)
   {
   echo "Apologies, an error has occurred.<br>";
   echo "Error Code: " . $_FILES["fileToUpload"]["error"] . "<br>";
   }

// Check if image file is a actual image or fake image
if($target_dir != $target_file){
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            // echo "<a>" . "File is an image - " . $check["mime"] . ".</a><br>";
            $uploadOk = 1;
        } else {
            echo "<a>" . "File is not an image.<br>";
            $uploadOk = 0;
        }
    }
} else {
    $uploadOk = 0;
}
// Check if file already exists
if (file_exists($target_file) && $uploadOk == 1) {
    echo "<a>" . "Sorry, file already exists.</a><br>";
    $uploadOk = 0;
}
// Check file size: Must be less than 8.3MB (This is set by AWS)                    
if ($_FILES["fileToUpload"]["size"] > 8388608  && $uploadOk == 1) {
    echo "<a>" . "Sorry, the file must be less than 8MB.</a><br>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif"  && $uploadOk == 1) {
    echo "<a>" . "Sorry, only JPG, JPEG, PNG & GIF files are allowed.</a><br>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<a>" . "Sorry, your file was not uploaded.</a><br>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "<a>" . "The file '". basename( $_FILES["fileToUpload"]["name"]). "' has been uploaded.</a><br>";
        list($width, $height) = getimagesize($target_file) ;
        echo "<a>" . "Image size is $width x $height</a><br>";
        shell_exec('aws s3 sync /var/www/html/uploads/ s3://rekognitiontest174/ ');
    } else {
        echo "<a>" . "Sorry, there was an error uploading your file.</a><br>"; 
    }

    $script = 'aws rekognition detect-labels --image \'{"S3Object":{"Bucket":"rekognitiontest174","Name":"' . $target_name . '"}}\' --region us-west-2 ';
    $rekognize = shell_exec($script);
    $labels_found = json_decode($rekognize,true);
  
    echo "<img style='display: block; margin-left: auto; margin-right: auto;' width='650' src='$target_file'>";
    echo "<a> Check the boxes of categories that apply to this image </a>";
    echo (' <form action="#"> <p> ');
    for($i = 0; $i < count($labels_found['Labels']); $i++){
           $category = $labels_found['Labels'][$i]['Name'];
           echo("<label> <input type='checkbox' display='inline-block' class='filled-in'/><span> $category &nbsp;&nbsp;&nbsp; </span> </label> ");
    }
    echo ('</p> </form>');
  }
  ?>
    <br>
    <a class="waves-effect waves-light btn-large" style="display: block; margin-left: auto; margin-right: auto;" onclick="goHome()" href="/">Home</a>
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

<script>
function goHome(){
  alert("test");  
<?php
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
    // #
    // # THE USER ID WILL BE CHANGED WHEN 
    // # GOOGLE SIGN-IN IS FULLY IMPLEMENTED
    // #
    // #####################################

    $sql = "INSERT INTO UploadedImages (user_id, image_name, filepath) values (10, '$target_name', '$target_file')";
    mysqli_query($conn, $sql);
    mysqli_close($conn);
  ?>


}
</script>

</body>

</html>
