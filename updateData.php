<?php

    session_start();

    //database info
    require_once('dbconfig.php');
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_DATABASE);

    //gathers session variables
    $name = $_SESSION["name"];
    $chat = $_SESSION["chat"];

    //prepares message to be sent to the webpage for rendering
    $data = [];
    $iter = 1;

    $result = mysqli_query($con, "select * from `$chat` order by `m_num` asc");
    while($row = mysqli_fetch_array($result)){
        $data[$iter] = $row;
        $iter++;
    }
    mysqli_close($con);

    //adds current user and emptyness information
    $data["current_user"] = $name;
    if(mysqli_num_rows($result) == 0){
        $data["empty"] = "true";
    }
    else{
        $data["empty"] = "false";
    }

    //returns data
    echo json_encode($data);
?>