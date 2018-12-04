<?php
include "../inc/dbinfo.inc";
require_once 'vendor/autoload.php';
session_start();

$cat = $_GET['category'];
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT filepath FROM UploadedImages WHERE image_id IN (SELECT image_id FROM ImageCategories WHERE category = '$cat');";
$results = $conn->query($sql);

echo('<div class ="boxen">');
if ($results->num_rows > 0) {
    while($row = $results->fetch_assoc()) {
        echo "<img class='boxen' src=" . $row['filepath'] . ">";
    }
}
echo("</div>");
echo('<div class="boxen"> <img class="boxen" src="uploads/DSC_8783.JPG" </div>');
?>