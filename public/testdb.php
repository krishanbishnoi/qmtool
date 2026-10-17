<?php
$conn = new mysqli(
    "188.241.187.31",
    "qdegrees_qmtool",
    "Civil@890",
    "qdegrees_qmtool"
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
?>