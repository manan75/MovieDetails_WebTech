<?php
//make connection object to mysql
$host = 'localhost';
$db   = 'movieReview';   
$user = 'root';         
$pass = 'manan2005';       

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

//API key
$OMDB_API_KEY = "4e2d9a9b";
?>
