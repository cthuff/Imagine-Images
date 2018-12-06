<?php
session_start();
$var = 1;
try {
    if ($var == 0) {
        //echo header('HTTP/1.0 400 Bad error');
	throw new Exception('Test error', 123);
    }
    echo json_encode(array(
        'result' => 'vanilla!',
    ));
} catch (Exception $e) {
    $_SESSION["array"] = array('error' =>  $e->getMessage());
}
?>