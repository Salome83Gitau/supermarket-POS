<?php
$servername= "localhost";
$username="Sally";
$password="5411y";
$dbname="POS";

// create connection to the db
$conn=new mysqli($servername,$username,$password,$dbname);
if($conn->connect_error){
  die("Connection failed:" .$conn->connect_error);
}
$conn->close();
  ?>

