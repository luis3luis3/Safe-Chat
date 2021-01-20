<?php
    session_start();

    //database info
    require_once('dbconfig.php');
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_DATABASE);

    $chat = $_SESSION["chat"];

    //empties the table for the specified chat
    mysqli_query($con, "delete from `$chat`");
    mysqli_close($con);
?>