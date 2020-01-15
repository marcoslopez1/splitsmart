<?php
//File to connect to the database

$servername = "";
$dBUsername = "";
$dBPassword = "";
$dBName = "";

$conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
$db = new PDO ('mysql:dbname='.$dBName.';host='.$servername,$dBUsername,$dBPassword);


//Checking the connection to the database and showing the error in case the connection didn't work
if (!$conn) {
  die("Connection failed: ".mysqli_connect_error());
}

?>
