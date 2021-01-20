<?php

    session_start();

    //database info
    require_once('dbconfig.php');
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_DATABASE);

    //gathers information from session and post variables
    $name = $_SESSION["name"];
    $chat = $_SESSION["chat"];
    $message = mysqli_escape_string($con, $_POST["message"]);

    $messageNum = mysqli_num_rows(mysqli_query($con, "select * from `$chat`"));

    //creates timestamp
    $time = date("hxixsa");
    $mydate=getdate(date("U"));
    $date = $mydate[mon] . "x" . $mydate[mday] . "x" . $mydate[year];
    $timestamp = $date . "x" . $time;

    //updates all message numbers by 1 and inserts new message
    mysqli_query($con,"update `$chat` set m_num = m_num + 1");
    mysqli_query($con, "insert into `$chat` (name, message, time, m_num) values ('$name','$message','$timestamp','1')");

    //deletes any overflow message
    if($messageNum == 20){
        mysqli_query($con, "delete from `$chat` where `m_num` = '21'");
    }

    //prepares data to be sent back for rendering
    $data = [];
    $iter = 1;

    $result = mysqli_query($con, "select * from `$chat` order by `m_num` asc");
    while($row = mysqli_fetch_array($result)){
        $data[$iter] = $row;
        $iter++;
    }
    mysqli_close($con);

    $data["current_user"] = $name;
    echo json_encode($data);
?>