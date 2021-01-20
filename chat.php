<?php
    session_start();

    //checks if session is v alid
    if(!isset($_SESSION["valid"])){
        $_SESSION["valid"] = "true";
    }
    else {
        session_destroy();
        header('Location: index.html');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Safe Chat:
        <?php
            session_start();
            echo stripcslashes($_SESSION["chat"]);
        ?>
    </title>
    <script src="http://code.jquery.com/jquery-1.11.3.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="UTF-8">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-71929754-1', 'auto');
        ga('send', 'pageview');
    </script>
    <script>
        $(document).ready();

        //wipes data on the front end and back end
        function wipeData(){
            $.ajax({
                url : "wipe.php",
                method : "POST",
                success:
                    function(data){
                        for(var i = 1; i <= 20; i++){

                            var divMessage = "#div" + i;
                            var divInfo = "#div" + i + "info";
                            var divTime = "#div" + i + "time";

                            $(divMessage).html("");
                            $(divInfo).html("");
                            $(divTime).html("");
                        }
                    }
            });
        }

        //counts characters and updates div
        function charCount() {
            var text = document.getElementById("message").value;
            var length = text.length;

            var newCount = "<h4><span class='label label-default'>" + "Character Count: " + length + "</span></h4>";

            $('#count').html(newCount);
        }
        window.setInterval(charCount, 250);

        //checks with server script to get updated messages and renders them appropriately
        function refreshData() {
            $.ajax({
                url: "updateData.php",
                method: "POST",
                success:
                    function(data){
                        var info = JSON.parse(data);
                        var name = info.current_user;
                        var status = info.empty;

                        for(var i = 1; i <= 20; i++){

                            var divMessage = "#div" + i;
                            var divInfo = "#div" + i + "info";
                            var divTime = "#div" + i + "time";
                            var divNum = "#div" + i + "num";

                            var message;
                            var information;
                            var time;
                            var num;

                            if(status == "true"){
                                message = "   ";
                                information = "   ";
                                time = "   ";
                                num = "   ";
                            }
                            else{
                                message = info[i]["message"];
                                information = info[i]["name"] + ":";
                                var real_name = info[i]["name"];
                                time = info[i]["time"];
                                num = i + ".";
                            }

                            $(divMessage).html(message);
                            $(divInfo).html(information);
                            $(divTime).html(time);
                            $(divNum).html(num);

                            //handles alignment based on the sender
                            if(real_name == name){
                                $(divMessage).css({"font-weight": "bold", "text-align" : "right", "padding-right" : "25px"});
                                $(divInfo).css({"font-weight": "bold", "text-align" : "left"});
                                $(divTime).css({"font-weight": "bold"});
                                $(divNum).css({"font-weight": "bold"});
                            }
                            else {
                                $(divMessage).css({"text-align" : "left"});
                                $(divInfo).css({"text-align" : "left"});
                            }
                        }
                    }
            });
        }
        window.setInterval(refreshData, 2500);

        //sends message to database and renders updated messages
        function sendMessage(){
            var text = document.getElementById("message").value;

            //not being used currently
            var alert = "";
            if(text == ""){
                alert = "Cannot send a blank message";
            }
            else if (text.length > 130){
                alert = "Messages cannot exceed 130 characters"
            }

            if(alert == ""){
                $.ajax({
                    url: "updateMessage.php",
                    method: "POST",
                    data:{message:text},
                    success:
                        function(data){

                            var info = JSON.parse(data);
                            var name = info.current_user;

                            for(var i = 1; i <= 20; i++){

                                var divMessage = "#div" + i;
                                var divInfo = "#div" + i + "info";
                                var divTime = "#div" + i + "time";
                                var divNum = "#div" + i + "num";

                                var message = info[i]["message"];
                                var information = info[i]["name"];
                                var time = info[i]["time"];
                                var num = i + ".";

                                $(divMessage).html(message);
                                $(divInfo).html(information+":");
                                $(divTime).html(time);
                                $(divNum).html(num);

                                if(information == name){
                                    $(divMessage).css({"font-weight": "bold", "text-align" : "right", "padding-right" : "25px"});
                                    $(divInfo).css({"font-weight": "bold", "text-align" : "left"});
                                    $(divTime).css({"font-weight": "bold"});
                                    $(divNum).css({"font-weight": "bold"});
                                }
                                else{
                                    $(divMessage).css({"text-align" : "left"});
                                    $(divInfo).css({"text-align" : "left"});
                                }
                            }
                        }
                });
                $("#message").val(null);
            }
            else{
                alert(alert);
            }
        }

    </script>
</head>

<body class="b2">
<div style="text-align: center;">
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <h1>
                <span class="h2 label label-default">Chatroom:
                    <?php
                        session_start();
                        echo stripcslashes($_SESSION["chat"]);
                    ?>
                </span>
            </h1>
        </div>
        <div class="col-sm-2"></div>
        <div class="col-sm-1"></div>
        <div class="col-sm-1 top15">
            <button class = "btn btn-primary push-left" onclick="wipeData()">
                <span class="glyphicon glyphicon-trash"></span>
            </button>
        </div>
    </div>

    <table class="top10" align="center" frame="box">
        <col width="30">
        <col width="200">
        <col width="80">
        <col width="890">

        <div class="row">
            <tr id="r20" >
                <td><div id="div20num">   </div></td>
                <td><div id="div20time">   </div></td>
                <td><div id="div20info">   </div></td>
                <td><div id="div20">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r19" >
                <td><div id="div19num">   </div></td>
                <td><div id="div19time">   </div></td>
                <td><div id="div19info">   </div></td>
                <td><div id="div19">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r18" >
                <td><div id="div18num">   </div></td>
                <td><div id="div18time">   </div></td>
                <td><div id="div18info">   </div></td>
                <td><div id="div18">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r17" >
                <td><div id="div17num">   </div></td>
                <td><div id="div17time">   </div></td>
                <td><div id="div17info">   </div></td>
                <td><div id="div17">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r16" >
                <td><div id="div16num">   </div></td>
                <td><div id="div16time">   </div></td>
                <td><div id="div16info">   </div></td>
                <td><div id="div16">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r15" >
                <td><div id="div15num">   </div></td>
                <td><div id="div15time">   </div></td>
                <td><div id="div15info">   </div></td>
                <td><div id="div15">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r14" >
                <td><div id="div14num">   </div></td>
                <td><div id="div14time">   </div></td>
                <td><div id="div14info">   </div></td>
                <td><div id="div14">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r13" >
                <td><div id="div13num">   </div></td>
                <td><div id="div13time">   </div></td>
                <td><div id="div13info">   </div></td>
                <td><div id="div13">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r12" >
                <td><div id="div12num">   </div></td>
                <td><div id="div12time">   </div></td>
                <td><div id="div12info">   </div></td>
                <td><div id="div12">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r11" >
                <td><div id="div11num">   </div></td>
                <td><div id="div11time">   </div></td>
                <td><div id="div11info">   </div></td>
                <td><div id="div11">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r10" >
                <td><div id="div10num">   </div></td>
                <td><div id="div10time">   </div></td>
                <td><div id="div10info">   </div></td>
                <td><div id="div10">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r9" >
                <td><div id="div9num">   </div></td>
                <td><div id="div9time">   </div></td>
                <td><div id="div9info">   </div></td>
                <td><div id="div9">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r8" >
                <td><div id="div8num">   </div></td>
                <td><div id="div8time">   </div></td>
                <td><div id="div8info">   </div></td>
                <td><div id="div8">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r7" >
                <td><div id="div7num">   </div></td>
                <td><div id="div7time">   </div></td>
                <td><div id="div7info">   </div></td>
                <td><div id="div7">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r6" >
                <td><div id="div6num">   </div></td>
                <td><div id="div6time">   </div></td>
                <td><div id="div6info">   </div></td>
                <td><div id="div6">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r5" >
                <td><div id="div5num">   </div></td>
                <td><div id="div5time">   </div></td>
                <td><div id="div5info">   </div></td>
                <td><div id="div5">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r4" >
                <td><div id="div4num">   </div></td>
                <td><div id="div4time">   </div></td>
                <td><div id="div4info">   </div></td>
                <td><div id="div4">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r3" >
                <td><div id="div3num">   </div></td>
                <td><div id="div3time">   </div></td>
                <td><div id="div3info">   </div></td>
                <td><div id="div3">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r2" >
                <td><div id="div2num">   </div></td>
                <td><div id="div2time">   </div></td>
                <td><div id="div2info">   </div></td>
                <td><div id="div2">   </div></td>
            </tr>
        </div>

        <div class="row">
            <tr id="r1" >
                <td><div id="div1num">   </div></td>
                <td><div id="div1time">   </div></td>
                <td><div id="div1info">   </div></td>
                <td><div id="div1">   </div></td>
            </tr>
        </div>
    </table>

    <input type="text" id="message" name="message" size="100" maxlength="110" onkeydown="if (event.keyCode == 13) document.getElementById('button').click()" autocomplete="off">

    <button class="btn btn-primary" id="button" name="button" value="Send" onclick="sendMessage();">
        <span class="glyphicon glyphicon-send"></span>
    </button>
    <div id="count" class="row col-center"><h4><span class="label label-default">Word Count: 0</span></h4></div>
</div>

<div id="footer" class="mastfoot" style="text-align: center;" >
    <div class="inner">
        <br>
        <br>
        </b><em>Safe Chat!</em>
    </div>
</div>
</body>
</html>
