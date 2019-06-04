<!DOCTYPE html>
<html>
<head>
    <title>Code Synchronize</title>
    <link href="statics/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="statics/favicon.ico"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="10">

    <style type="text/css" media="screen">
        #editor {
            height: 300px;
            position: relative;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    </style>
</head>
<body>

<?php

header("content-type:text/html;charset=utf-8");

$conn = mysql_connect('localhost', 'xxx', 'xxx');
if (!$conn) {
    die("数据库连接失败" . mysql_error());
}

mysql_select_db("test");
mysql_query("set names utf8"); //没有 -

?>

<div class="container">
    <div class="row">
        <?php
        $sql = "SELECT DISTINCT user_name FROM sync_code";
        $res = mysql_query($sql, $conn);  //得到的资源 resource

        if(isset($_GET['user_name'])) {
            $user_name = $_GET['user_name'];
        }
        while($row=mysql_fetch_row($res)){
            if($user_name==$row[0]){
                echo "<h4><a href='index.php?user_name=".$row[0]."' class='btn btn-danger' role='button'>$row[0]</a></h4>";
            }else{
                echo "<h4><a href='index.php?user_name=".$row[0]."' class='btn btn-success' role='button'>$row[0]</a></h4>";
            }

        }
        ?>
    </div>
    <div class="row">
        <div class="col-md-6" style="padding-top:20px;">
            <?php
            if(isset($_GET['user_name'])){
                $user_name = $_GET['user_name'];
                $sql = "SELECT * FROM sync_code WHERE user_name='{$user_name}' ORDER BY sync_time desc LIMIT 5";
            }else{
                $sql = "SELECT * FROM sync_code ORDER BY sync_time desc LIMIT 5";
            }
            $res = mysql_query($sql, $conn);  //得到的资源 resource
            while ($row = mysql_fetch_row($res)) {
                echo "<h4>user : $row[1]</h4>";
                echo "<h4>time : " . date('Y-m-d H:i:s', $row[2]) . "</h4>";
                echo "<textarea data-editor='c_cpp' class=\"form-control\">".trim($row[3])."</textarea>";
            }
            ?>
        </div>
    </div>
</div>


</body>

<?php
mysql_free_result($res);//释放资源 resource
mysql_close($conn);//脚本执行完自动关闭  关闭连接 conn
?>

<script src="//cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/ace/1.2.6/ace.js"></script>

<script>
    $('textarea').each(function () {
        var textarea = $(this);
        var mode = textarea.data('editor');
        var editDiv = $('<div>', {
            position: 'relative',
            width: textarea.width(),
            overflow: 'auto',
            width: '100%',
            height: '300px',
            'class': textarea.attr('class')
        }).insertBefore(textarea);

        textarea.css('visibility', 'hidden'); //隐藏textarea

        var editor = ace.edit(editDiv[0]);
        //editor.renderer.setShowGutter(false);
        editor.getSession().setValue(textarea.val());
        editor.setTheme("ace/theme/chrome");
        editor.setFontSize(12);
        editor.setOptions({
            enableBasicAutocompletion: true,
            enableSnippets: true,
            enableLiveAutocompletion: true
        });
        editor.getSession().setMode("ace/mode/" + mode);
    });
</script>
</html>
