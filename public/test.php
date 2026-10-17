<?php 

$servername = "127.0.0.1";
$username = "qdegrees_qmtool";
$password = '5!stWhx*W<t"HQ;F';

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";


?>