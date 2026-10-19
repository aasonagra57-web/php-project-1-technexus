<?php 
// simply used for connection with database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "technexus";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if(!$conn){
          die("SORRY we failed to connect: ".mysqli_connect_error());
        }
?>