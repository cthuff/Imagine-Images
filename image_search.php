<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: Arial;
}

.header {
    text-align: center;
    padding: 32px;
}

.big-boxen {
    display: -ms-flexbox; /* IE10 */
    display: flex;
    -ms-flex-wrap: wrap; /* IE10 */
    flex-wrap: wrap;
    padding: 0 4px;
}

/* Create four equal columns that sits next to each other */
.boxen {
    -ms-flex: 25%; /* IE10 */
    flex: 25%;
    max-width: 25%;
    padding: 0 4px;
}

.boxen img {
    margin-top: 8px;
    vertical-align: middle;
}

/* Responsive layout - makes a two column-layout instead of four columns */
@media screen and (max-width: 800px) {
    .boxen {
        -ms-flex: 50%;
        flex: 50%;
        max-width: 50%;
    }
}

/* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
    .boxen {
        -ms-flex: 100%;
        flex: 100%;
        max-width: 100%;
    }
}
</style>
<div class="big-boxen">
<?php
include "../inc/dbinfo.inc";
require_once 'vendor/autoload.php';
session_start();

$image_counter = 0;
$cat = $_GET['category'];
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
// Check connection
if (!$conn) {
   die("Connection failed: " . mysqli_connect_error());
}
$sql = "SELECT image_name FROM UploadedImages WHERE image_id IN (SELECT image_id FROM ImageCategories WHERE category = '$cat');";
$results = $conn->query($sql);
$size = $results->num_rows / 4;
//echo $size;
echo('<div class ="boxen">');
if ($results->num_rows > 0) {
    while($row = $results->fetch_assoc()) {
        if($image_counter >= ceil($size)){
	    echo '</div></br><div class ="boxen">';
	    echo "<img src=watermarked/" . $row['image_name'] . " class='test' style='width:100%;' onclick=window.location.replace('buy.php?name=". $row['image_name']. "')>";
            $image_counter = 0;
	    //echo "cell1";
	} else {
	    echo "<img src=watermarked/" . $row['image_name'] . " style='width:100%;' onclick=window.location.replace('buy.php?name=". $row['image_name']. "')>";
	    $image_counter++;
	    //echo "cell2";
	}
    }
}
echo("</div>");

?>
