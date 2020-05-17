<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);
$dbserver ='localhost';        // Your database server

$dbuser ='';        // Your mysql username

$dbpass ='';                // Your mysql password

$dbname ='';        // Your mysql database name
$MySQLConnection = mysqli_connect( $dbserver, $dbuser, $dbpass, $dbname) or die(mysqli_error());
if (isset($_GET["saveGame_Show"])) {
    $name = isset($_GET["name"]) ? trim(htmlspecialchars(($_GET["name"]), ENT_QUOTES)) : "";
    $date_event = isset($_GET["date_event"]) ? convertdate(trim(htmlspecialchars(($_GET["date_event"]), ENT_QUOTES))) : "";
    $time_event = isset($_GET["time_event"]) ? trim(htmlspecialchars(($_GET["time_event"]), ENT_QUOTES)) : "";
    $question = isset($_GET["question"]) ? trim(htmlspecialchars(($_GET["question"]), ENT_QUOTES)) : "";
    $answer1 = isset($_GET["answer1"]) ? trim(htmlspecialchars(($_GET["answer1"]), ENT_QUOTES)) : "";
    $answer2 = isset($_GET["answer2"]) ? trim(htmlspecialchars(($_GET["answer2"]), ENT_QUOTES)) : "";
    $answer3 = isset($_GET["answer3"]) ? trim(htmlspecialchars(($_GET["answer3"]), ENT_QUOTES)) : "";
    $answer4 = isset($_GET["answer4"]) ? trim(htmlspecialchars(($_GET["answer4"]), ENT_QUOTES)) : "";
    $answer = isset($_GET["answer"]) ? trim(htmlspecialchars(($_GET["answer"]), ENT_QUOTES)) : "";

    $question=explode(',',$question);
    $answer1=explode(',',$answer1);
    $answer2=explode(',',$answer2);
    $answer3=explode(',',$answer3);
    $answer4=explode(',',$answer4);
    $answer=explode(',',$answer);

    $consulta = "insert into Game_Show (name, date_event, time_event, date) values ( '" . $name . "', '" . $date_event . "', '" . $time_event . "', CURRENT_TIMESTAMP);";
    mysqli_query($MySQLConnection, $consulta);
    $id= mysqli_insert_id($MySQLConnection);
    $num=1;
    $msg='<hr/><h1>Question and Answers</h1>';
    for($x=0;$x<count($question);$x++) {
        $consulta = "insert into Game_Questions (id_game, question, answer1, answer2, answer3, answer4, answer, date) values ( '" . $id . "', '" . $question[$x] . "', '" . $answer1[$x] . "', '" . $answer2[$x] . "', '" . $answer3[$x] . "', '" . $answer4[$x] . "', '" . $answer[$x] . "', CURRENT_TIMESTAMP);";
        mysqli_query($MySQLConnection, $consulta);
        $msg.='<div>
            <strong>Question #'.$num.'</strong><br>
            '.$question[$x].'<br>
            <strong>Answers</strong><br>
            A) '.$answer1[$x].'<br>
            B) '.$answer2[$x].'<br>
            C) '.$answer3[$x].'<br>
            D) '.$answer4[$x].'<br>
            Correct Answer: '.$answer[$x].'
        </div><hr />';
        $num++;
    }
    echo $id;
    $email_to='anthonyfchaves@gmail.com';

    $subject = 'Game Show Form';
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $msg = '<h1>Client Information</h1><br>
    <div>
		<strong>Name of Client<span style="color:red;">*</span></strong><br>
		'.$name.'
	</div>
	<div>
		<strong>Date of Event<span style="color:red;">*</span></strong><br>
		'.$date_event.'
	<div>
		<strong>Time of Event<span style="color:red;">*</span></strong><br>
		'.$time_event.'
	</div>'.$msg;
    // send email
    if($email_to!='')
        mail($email_to, $subject, $msg, $headers);
    die();
}
?>
<html>
<head>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/datatables.min.js" type="text/javascript"></script>
    <link href="css/datatables.min.css" rel="stylesheet" type="text/css" />
    <script src="js/bootstrap-datepicker.js"></script>
    <link rel="stylesheet" href="css/datepicker.css">
    <style>
        input{
            width: 100%;
        }
        textarea{
            width: 300px !important;
        }
        #game_form,#questions{
            text-align: center;
            width: 30%;
            margin: auto;
        }
    </style>
</head>
<body>
<div id="game_form">
    <h4 class='modal-title'>New Game Show</h4>
    <div style="text-align: left">
        Name of Client<span style='color:red'>*</span><br>
        <input class='inputs' id='name' type='text'>
        <div>Date of Event<span style='color:red'>*</span><br>
            <div class='input-append'>
                <input class='inputs' id='date_event' type='text'>
                <span class='add-on' onClick='$("#date_event").datepicker("show");'>
                    <i class='icon-calendar'></i>
                </span>
            </div>
        </div>
        <div>Time of Event<span style='color:red'>*</span><br>
            <select id="time_event">
                <option value="">Select an Option</option>
                <option value="Eastern Standard Time (EST)">Eastern Standard Time (EST)</option>
                <option value="Pacific Standard Time (PST)">Pacific Standard Time (PST)</option>
                <option value="Mountain Standard Time (MST)">Mountain Standard Time (MST)</option>
                <option value="Atlantic Standard Time (AST)">Atlantic Standard Time (AST)</option>
                <option value="Central Standard Time (CST)">Central Standard Time (CST)</option>
            </select>
        </div><br>
    </div>
    <a class="btn btn-primary" onclick="next()">Next</a>
</div>
<div id="questions" style="display: none;">
    <h4 class='modal-title'>Questions</h4>
    <div style="text-align: left">
        Question:<br>
        <input id="question" type="text"/><br>
        A) Answer:<br>
        <input id="answer1" type="text"/><br>
        B) Answer:<br>
        <input id="answer2" type="text"/><br>
        C) Answer:<br>
        <input id="answer3" type="text"/><br>
        D) Answer:<br>
        <input id="answer4" type="text"/><br>
        Select the correct answer to the question:<br>
        <select id="answer">
            <option value="">Select</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select><br><br>
    </div>
    <a class="btn btn-warning" onclick="next_question()" id="add_another">Add another question</a>
    <a class="btn btn-primary" onclick="saveGame_Show()">Send</a>
</div>
<script>
    var question=[];
    var answer1=[];
    var answer2=[];
    var answer3=[];
    var answer4=[];
    var answer=[];
    $( document ).ready(function() {
        $("#date_event").datepicker({format: "mm/dd/yyyy", todayHighlight:true, autoclose:true});
    });
    function next(){
        if ($.trim($("#name").val()) == "") {
            alert("Name of Client cannot be empty");
            $("#name").focus();
        } else if ($.trim($("#date_event").val()) == "") {
            alert("Date of Event cannot be empty");
            $("#date_event").focus();
        } else if ($.trim($("#time_event").val()) == "") {
            alert("Please select the time of event");
            $("#time_event").focus();
        } else {
            $("#game_form").hide();
            $("#questions").show();
            $("#question").focus();
        }
    }
    function next_question(){
        if ($.trim($("#question").val()) == "") {
            alert("Question cannot be empty");
            $("#question").focus();
        }
        else if ($.trim($("#answer1").val()) == "") {
            alert("Answer 1 cannot be empty");
            $("#answer1").focus();
        }
        else if ($.trim($("#answer").val()) == "") {
            alert("Please select an answer");
            $("#answer").focus();
        }
        else {
            question.push($.trim($("#question").val()));
            answer1.push($.trim($("#answer1").val()));
            answer2.push($.trim($("#answer2").val()));
            answer3.push($.trim($("#answer3").val()));
            answer4.push($.trim($("#answer4").val()));
            answer.push($.trim($("#answer").val()));
            if(question.length==9)
                $("#add_another").hide();
            $("#question").val('');
            $("#answer1").val('');
            $("#answer2").val('');
            $("#answer3").val('');
            $("#answer4").val('');
            $("#answer").val('');
            $("#question").focus();
        }
    }
    function saveGame_Show() {
        if (question.length==0&&$.trim($("#question").val()) == "") {
            alert("Question cannot be empty");
            $("#question").focus();
        }
        else if (question.length==0&&$.trim($("#answer1").val()) == "") {
            alert("Answer 1 cannot be empty");
            $("#answer1").focus();
        }
        else if (question.length==0&&$.trim($("#answer").val()) == "") {
            alert("Please select an answer");
            $("#answer").focus();
        }
        else if ($.trim($("#name").val()) == "") {
            alert("Name of Client cannot be empty");
            $("#name").focus();
        } else if ($.trim($("#date_event").val()) == "") {
            alert("Date of Event cannot be empty");
            $("#date_event").focus();
        } else if ($.trim($("#time_event").val()) == "") {
            alert("Please select the time of event");
            $("#time_event").focus();
        } else {
            question.push($.trim($("#question").val()));
            answer1.push($.trim($("#answer1").val()));
            answer2.push($.trim($("#answer2").val()));
            answer3.push($.trim($("#answer3").val()));
            answer4.push($.trim($("#answer4").val()));
            answer.push($.trim($("#answer").val()));

            $.ajax({
                url: "?saveGame_Show",
                cache: false,
                data: {
                    name: $("#name").val(),
                    date_event: $("#date_event").val(),
                    time_event: $("#time_event").val(),
                    question:question.toString(),
                    answer1:answer1.toString(),
                    answer2:answer2.toString(),
                    answer3:answer3.toString(),
                    answer4:answer4.toString(),
                    answer:answer.toString()
                },
                async: true
            }).done(function (data) {
                if (data > 0) {
                    $("#questions").html("Thanks we look forward to customizing your show.");
                }
            });
        }
    }
</script>
</body>
</html>
<?php
mysqli_close($MySQLConnection);
function convertdate($date){
    if($date=='')
        return '';
    //echo $date;
    $date = explode('/',$date);
    $date = $date[2] . '-' .$date[0] . '-' . $date[1];
    return $date;
}
?>
