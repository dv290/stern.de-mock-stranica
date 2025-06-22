<?php
$servername = "localhost"; 
$username = "root";      
$password = "";            
$database = "vijesti_db";  

$dbc = mysqli_connect($servername, $username, $password, $database);

if (!$dbc) {
    die("Konekcija na bazu podataka neuspješna: " . mysqli_connect_error());
}

mysqli_set_charset($dbc, "utf8");

?>