<?php
$servername = "YourServer";//localhost
$username = "YourUsername";//root
$password = "YourPassword";//"
$dbName = "YourDBName";

//create connection

$conn = new mysqli($servername,$username,$password,$dbName);

//check connection

if($conn->connect_error){
    die("connection failed:". $conn->connect_error);
}
// echo "Connected successfully";
?>