<!--Created by Craig Huff
11/28/18
CS 174 Final Project - Imagine Images
  -->
<?php
include "../inc/dbinfo.inc";
session_start();

if($_SESSION["homeURL"] !== "/dashboard.php"){
echo '<meta http-equiv="refresh" content="0; url=/">';
exit(0);
}
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
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"> 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

  <!-- Compiled and minified JavaScript -->
  <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/init.js"></script>
  <script src="js/materialize.js"></script>
  <script src="js/materialize.min.js"></script>
  
</head>
<body>
  <nav class="light-blue" style="line-height: 0px"role="navigation">
    <div class="nav-wrapper" >
      <a id="logo-container" href='<?php echo ($_SESSION["homeURL"]);?>' class="brand-logo amber-text text-accent-2 center hide-on-small-and-down" style="padding-top:30px;">Imagine Images</a>

    <ul id="nav-mobile" class="right">
    	<li><div class="right g-signin2 hide-on-small-and-down" style ="display:none;" data-onsuccess="onSignIn"></div></li>
        <li><div class="right btn amber hide-on-small-and-down" style="margin-top:14px; margin-right:30px;" onclick="signOut()"><span class="black-text">Sign Out</span></div></li>
        <li><div class="right btn amber hide-on-med-and-up" style ="margin-top:10px; margin-right:10px" onclick="signOut()"><span class="black-text">Sign Out</span></div></li>
      </ul>
    <ul>
         <li><i class="large material-icons left hide-on-small-and-down" style="padding-left:30px;">camera_roll</i></li>
         <li><i class="large material-icons left hide-on-med-and-up" style="padding-left:13px;">camera_roll</i></li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <div class="section">


<?php
    
$target_dir = "uploads/";
$uploadOk = 1;
$target_name = preg_replace('/\s+/', '', basename($_FILES["fileToUpload"]["name"]));
$target_file = $target_dir . $target_name;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if ($_POST["newFileName"] != ""){
    $target_name = $_POST["newFileName"] . "." .$imageFileType;
    $target_file = $target_dir . $target_name;
}

//Taken from W3Schools
//https://www.w3schools.com/PHP/php_file_upload.asp


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
        echo "<a>" . "The file '". $target_name . "' has been uploaded.</a><br>";
        list($width, $height) = getimagesize($target_file) ;
        echo "<a>" . "Image size is $width x $height</a><br>";
        shell_exec('aws s3 sync /var/www/html/uploads/ s3://rekognitiontest174/ ');
    } else {
        echo "<a>" . "Sorry, there was an error uploading your file.</a><br>"; 
    }

    $script = 'aws rekognition detect-labels --image \'{"S3Object":{"Bucket":"rekognitiontest174","Name":"' . $target_name . '"}}\' --region us-west-2 ';
    $rekognize = shell_exec($script);
    $labels_found = json_decode($rekognize,true);
    $_SESSION["categories"] = $labels_found['Labels'];
    echo "<img style='display: block; margin-left: auto; margin-right: auto;' width='650' src='$target_file'>";
    
    if (count($labels_found['Labels'])) {
       echo "<a> Check the boxes of categories that apply to this image </a>";
       echo (' <form id="test" action="#"> <p> ');
       for($i = 0; $i < 5; $i++){
           $category = $labels_found['Labels'][$i]['Name'];
	   //$_SESSION["cat".$i] = $category;
           echo("<label> <input id='cat_$i' type='checkbox' display='inline-block' class='filled-in'/><span> $category &nbsp;&nbsp;&nbsp; </span> </label> ");
       }
       echo ('</p> </form>');
    }
  }
  ?>
    <br>
    <a class="waves-effect waves-dark amber btn-large" style="display: block; margin-left: auto; margin-right: auto;" onclick="goHome()" >Home</a>
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
  
<script>
function goHome(){

<?php if ($uploadOk == 1) {
echo('var str = "cat_";
var check = document.getElementById(str.concat("0")).checked;
var categories = new Array();
for(var i = 0; i < 5; i++) {
    if(document.getElementById(str.concat(i)).checked == true) {
        categories.push(i);
    }
}');
echo("$.ajax(
    {
      type: 'post',
      url: 'upload_image.php',
      data: {cats: categories, name: '$target_name', path: '$target_file' }
    })\n");
}?>
window.location.replace('/dashboard.php');
}
</script>

</body>

</html>
