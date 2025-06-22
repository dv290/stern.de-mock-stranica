<?php

include 'dbconn.php'; 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_clanka = mysqli_real_escape_string($dbc, $_GET['id']); 

    $query = "DELETE FROM vijesti WHERE id = ?";

    $stmt = mysqli_prepare($dbc, $query);

    if ($stmt === false) {
        die("Greška pri pripremi SQL upita za brisanje: " . mysqli_error($dbc));
    }

    mysqli_stmt_bind_param($stmt, 'i', $id_clanka);

    if (mysqli_stmt_execute($stmt)) {
        echo "Vijest s ID: $id_clanka je uspješno obrisana."; 
    } else {
        echo "Greška pri brisanju vijesti: " . mysqli_error($dbc); 
    }

    mysqli_stmt_close($stmt);

    header('Location: administrator.php');
    exit;

} else {
    header('Location: administrator.php');
    exit;
}

?>