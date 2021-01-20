<?php
    session_start();

    //database info
    require_once('dbconfig.php');
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_DATABASE);

    $name = mysqli_escape_string($con, $_POST["name"]);
    $chat = mysqli_escape_string($con, $_POST["chat"]);

    $_SESSION["chat"] = $chat;
    $_SESSION["name"] = $name;

    

     //if(!mysqli_num_rows(mysqli_query($con,"SHOW TABLES LIKE '".`$chat`."'"))==1){
        //table doesn't exits
        mysqli_query($con,"CREATE TABLE IF NOT EXISTS `$chat` (
          `name` text NOT NULL,
          `message` text NOT NULL,
          `time` text NOT NULL,
          `m_num` int )");
      
   // }
    //close db conection
    mysqli_close($con);
   header("Refresh:0; url=chat.php");
?>