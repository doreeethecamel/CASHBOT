<?php 
 session_start();
 // initializing variables
 $username = "";
 $email    = "";
 $errors = array();
 $status = "";
 // connect to the database
 $conn = mysqli_connect("localhost","root","","privacybot") or die("Couldn't connect");
 define('ENCRYPTION_KEY', '12345678901234567890123456789012'); // Example 32-byte key
define('ENCRYPTION_IV', substr(hash('sha256', 'my-secret-iv-seed'), 0, 16)); // Example IV seed


 
?>