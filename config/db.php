<?php
$servername = "freedb.tech";//localhost
$username = "freedbtech_randomroot";//root
$password = "root";//"
$dbName = "freedbtech_xkcdrandomcomics";

//create connection

$conn = new mysqli($servername,$username,$password,$dbName);

//check connection

if($conn->connect_error){
    die("connection failed:". $conn->connect_error);
}
// echo "Connected successfully";
?>