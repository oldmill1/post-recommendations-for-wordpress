<?php 
if ( ! isset($_POST) || empty($_POST) ) { 
	die("Sensor readings negative, captain.");  
} 

$wordpress = $_POST['abspath'] . 'wp-load.php'; 
include($wordpress);

$get = $_POST['wp-recommendations-form-options-type']; 
echo bloginfo('name'); 

