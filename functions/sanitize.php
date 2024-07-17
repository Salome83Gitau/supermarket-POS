
 <?php

 function test_input($data) {
     $data = trim($data); //strip unnecessary white space
     $data = stripslashes($data); //remove /
     $data = htmlspecialchars($data); //convert HTML special characters to HTML entities
     return $data;
 }
 ?>
 