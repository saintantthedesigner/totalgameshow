<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);
$dbserver ='localhost';        // Your database server

$dbuser ='';        // Your mysql username

$dbpass ='';                // Your mysql password

$dbname ='';        // Your mysql database name
$MySQLConnection = mysqli_connect( $dbserver, $dbuser, $dbpass, $dbname) or die(mysqli_error());
if (isset($_GET["saveGame_Show"])) {
    $id = isset($_GET["id"]) ? trim(htmlspecialchars(($_GET["id"]), ENT_QUOTES)) : "";
    $name = isset($_GET["name"]) ? trim(htmlspecialchars(($_GET["name"]), ENT_QUOTES)) : "";
    $date_event = isset($_GET["date_event"]) ? convertdate(trim(htmlspecialchars(($_GET["date_event"]), ENT_QUOTES))) : "";
    $time_event = isset($_GET["time_event"]) ? trim(htmlspecialchars(($_GET["time_event"]), ENT_QUOTES)) : "";
    $edit = isset($_GET["edit"]) ? trim(htmlspecialchars(($_GET["edit"]), ENT_QUOTES)) : "";

    $consulta = "update Game_Show set name = '" . $name . "', date_event = '" . $date_event . "', time_event = '" . $time_event . "' where id = " . $id;
    mysqli_query($MySQLConnection, $consulta);
    echo $id;
    die();
}
if (isset($_GET["json"])) {
    $id = isset($_GET["id"]) ? trim(htmlspecialchars(($_GET["id"]), ENT_QUOTES)) : "";
    $where='';
    if((int)$id>0)
        $where.=' and a.id = '.(int)$id;
    $rs = mysqli_query($MySQLConnection, "select a.* from Game_Show a where 1 ".$where." order by a.id desc");
    $json=array();
    $x=0;
    while ($row = mysqli_fetch_object($rs)) {
        $row->name = (htmlspecialchars_decode($row->name,ENT_QUOTES));
        $row->date_event = correctdate($row->date_event);
        $row->time_event = (htmlspecialchars_decode($row->time_event,ENT_QUOTES));

        $rs2 = mysqli_query($MySQLConnection, "select a.* from Game_Questions a where a.id_game = ".$row->id." order by a.id asc");
        $num=1;
        $questions=array();
        while ($row2 = mysqli_fetch_object($rs2)) {
            $questions[$num-1]['question']=htmlspecialchars_decode($row2->question,ENT_QUOTES);
            $questions[$num-1]['answer1']=htmlspecialchars_decode($row2->answer1,ENT_QUOTES);
            $questions[$num-1]['answer2']=htmlspecialchars_decode($row2->answer2,ENT_QUOTES);
            $questions[$num-1]['answer3']=htmlspecialchars_decode($row2->answer3,ENT_QUOTES);
            $questions[$num-1]['answer4']=htmlspecialchars_decode($row2->answer4,ENT_QUOTES);
            $questions[$num-1]['answer']=htmlspecialchars_decode($row2->answer,ENT_QUOTES);
            $num++;
        }
        $json[$x]['name']=$row->name;
        $json[$x]['date_event']=$row->date_event;
        $json[$x]['time_event']=$row->time_event;
        $json[$x]['questions']=$questions;
        $x++;
    }
    echo json_encode($json);
    die();
}
function viewGame_Show()
{
    global $MySQLConnection;
    mysqli_set_charset($MySQLConnection, "utf8");

    $contenido = "
    <table data-filter-text-only='true' data-page-size='20' class='tabla' style='margin-top:10px'><thead>
    <tr style='background-color:#333;color:white;'>
        <th colspan='4'>Game Show</th>
    </tr>
    <tr style='background-color:#333;color:white;'>
        <th data-hide='phone' style='width:75px'></th>
        <th>Name of Client</th>
        <th>Date of Event</th>
        <th>Time of Event</th>
    </tr></thead><tbody>";
    $rs = mysqli_query($MySQLConnection, "select a.* from Game_Show a where 1 order by a.id desc");
    $x = 1;
    while ($row = mysqli_fetch_object($rs)) {
        $row->name = (htmlspecialchars_decode($row->name,ENT_QUOTES));
        $row->date_event = correctdate($row->date_event);
        $row->time_event = (htmlspecialchars_decode($row->time_event,ENT_QUOTES));
        $contenido .= "<tr style='background-color:#eee;' id='row-" . $row->id . "'>
            <td style='width:175px'>
                <img style='width:25px;' src='images/edit.png' onClick='editGame_Show(" . $row->id . ")'/>
                <a class='btn btn-primary' href='?json&id=".$row->id."' target='_blank'>Export to JSON</a>
            </td>
            <td>" . $row->name . "</td>
            <td>" . $row->date_event . "</td>
            <td>" . $row->time_event . "</td>
        </tr>
        ";

        $rs2 = mysqli_query($MySQLConnection, "select a.* from Game_Questions a where a.id_game = ".$row->id." order by a.id asc");
        $num=1;
        while ($row2 = mysqli_fetch_object($rs2)) {
            $contenido.='<tr><td colspan="4">
               <table width="100%">
                    <td><strong>Question #'.$num.'</strong><br>
                    '.htmlspecialchars_decode($row2->question,ENT_QUOTES).'</td>
                    <td>A) '.htmlspecialchars_decode($row2->answer1,ENT_QUOTES).'</td>
                    <td>B) '.htmlspecialchars_decode($row2->answer2,ENT_QUOTES).'</td>
                    <td>C) '.htmlspecialchars_decode($row2->answer3,ENT_QUOTES).'</td>
                    <td>D) '.htmlspecialchars_decode($row2->answer4,ENT_QUOTES).'</td>
                    <td>Correct Answer: <strong>'.htmlspecialchars_decode($row2->answer,ENT_QUOTES).'</strong></td>
                </table>        
            </td></tr>';
            $num++;
        }
        $x++;
    }
    $contenido .= "</tbody><tfoot><tr id='footer'><th colspan='4'><div class='pagination pagination-centered hide-if-no-paging'></div></th></tr></tfoot></table>
    <script>$(function () { $('.tabla').DataTable();});</script>";
    return $contenido;
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
<a class="btn btn-primary" href="?json" target="_blank">Export to JSON</a><br>
<?php echo viewGame_Show();?>
<script>
    function editGame_Show(id) {
        edit = true;
        idtemp = id;
        if ($("#dataConfirmModal").length) {
            $("#dataConfirmModal").modal("hide");
            $("#dataConfirmModal").remove();
        }
        if (!$("#dataConfirmModal").length) {
            $("body").append("<div id='dataConfirmModal' aria-hidden='true' class='modal fade' data-backdrop='true' tabindex='-1' ><div class='modal-dialog'><div class='modal-content'><div class='modal-header' style='background-color:cornflowerBlue'>       <h4 class='modal-title'>Edit Game show</h4>   <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span><span class='sr-only'></span></button>       </div>      <div class='modal-body'><table style='width:100%' align='center'><div>Name of Client<span style='color:red'>*</span><br><input class='inputs' id='name' type='text'></div><div>Date of Event<span style='color:red'>*</span><br><div class='input-append'><input class='inputs' id='date_event' type='text'><span class='add-on' onClick='$(\"#date_event\").datepicker(\"show\");'><i class='icon-calendar'></i></span></div></div><div>Time of Event<span style='color:red'>*</span><br><select id=\"time_event\">\n" +
                "                <option value=\"\">Select an Option</option>\n" +
                "                <option value=\"Eastern Standard Time (EST)\">Eastern Standard Time (EST)</option>\n" +
                "                <option value=\"Pacific Standard Time (PST)\">Pacific Standard Time (PST)</option>\n" +
                "                <option value=\"Mountain Standard Time (MST)\">Mountain Standard Time (MST)</option>\n" +
                "                <option value=\"Atlantic Standard Time (AST)\">Atlantic Standard Time (AST)</option>\n" +
                "                <option value=\"Central Standard Time (CST)\">Central Standard Time (CST)</option>\n" +
                "            </select></table></div>      <div class='modal-footer'>       <button type='button' class='btn btn-danger' data-dismiss='modal'>CLOSE</button> <button type='button' class='btn btn-primary' onclick='saveGame_Show()'>UPDATE</button>              </div>    </div><!-- /.modal-content -->  </div><!-- /.modal-dialog --></div>");
        }
        $("#dataConfirmModal").modal({show: true});
        $("#date_event").datepicker({format: "mm/dd/yyyy"});
        $("#name").val($("#row-" + id + " td:nth-child(2)").text());
        $("#date_event").datepicker("update", $("#row-" + id + " td:nth-child(3)").text());
        $("#time_event").val($("#row-" + id + " td:nth-child(4)").text());
        return false;
    }

    function saveGame_Show() {
        if ($.trim($("#name").val()) == "") {
            alert("Name of Client cannot be empty");
            $("#name").focus();
        } else if ($.trim($("#date_event").val()) == "") {
            alert("Date of Event cannot be empty");
            $("#date_event").focus();
        } else if ($.trim($("#time_event").val()) == "") {
            alert("Time of Event cannot be empty");
            $("#time_event").focus();
        } else {
            $.ajax({
                url: "?saveGame_Show",
                cache: false,
                data: {
                    id: idtemp,
                    edit: edit,
                    name: $("#name").val(),
                    date_event: $("#date_event").val(),
                    time_event: $("#time_event").val()
                },
                async: true
            }).done(function (data) {
                if (data > 0) {
                    alert("La informacion ha sido guardada.");
                    location.reload();
                }
            });
        }
    }

    var edit = false;
    var idtemp = 0;
</script>
</body>
</html>
<?php
function correctdate($date){
    if($date == '0000-00-00'||$date=='')
        return '';
    //echo $date;
    $date = explode('-',$date);
    $date = $date[1] . '/' .$date[2] . '/' . $date[0];
    return $date;
}
function convertdate($date){
    if($date=='')
        return '';
    //echo $date;
    $date = explode('/',$date);
    $date = $date[2] . '-' .$date[0] . '-' . $date[1];
    return $date;
}
mysqli_close($MySQLConnection);
?>