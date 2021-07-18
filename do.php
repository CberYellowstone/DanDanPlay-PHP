<?php
define('IN_SYS', TRUE);
include_once 'function.php';

if(isCil()){
    echo(date('Y/m/d H:i:s', time()).": 正在生成缩略图...".PHP_EOL);
    mkpicForRoot($video_root_path);
    echo(date('Y/m/d H:i:s', time()).": 正在识别番剧...".PHP_EOL);
    saveVideoInformationForRoot($video_root_path);
    echo(date('Y/m/d H:i:s', time()).": 正在下载弹幕...".PHP_EOL);
    downloadCommentForRoot($video_root_path);
    echo(date('Y/m/d H:i:s', time()).": 任务完成".PHP_EOL);
    exit();
}

@header("Cache-Control: no-cache, must-revalidate");

function checkRefer($source=0){
    if(!$_SERVER['HTTP_REFERER']){
        //@header("refresh:1;url=./do.php");
        @header('Location: ./do.php');
    }
}

function mkContainer($str){
    echo('<div class="container">'.$str.'</div>');
}

function doTask(){
    if(!$_GET['step']){
        mkContainer('正在准备执行任务');
        @header("refresh:1;url=./do.php?step=1");
    } elseif($_GET['step']==1){
        checkRefer();
        mkContainer('正在生成视频缩略图...');
        @header("refresh:1;url=./do.php?step=1.5");
    } elseif($_GET['step']==1.5){
        checkRefer();
        mkpicForRoot($GLOBALS['video_root_path']);
        @header("refresh:1;url=./do.php?step=2");
    } elseif($_GET['step']==2){
        checkRefer();
        mkContainer('正在识别番剧...');
        @header("refresh:1;url=./do.php?step=2.5");
    } elseif($_GET['step']==2.5){
        checkRefer();
        saveVideoInformationForRoot($GLOBALS['video_root_path']);
        @header("refresh:1;url=./do.php?step=3");
    } elseif($_GET['step']==3){
        checkRefer();
        mkContainer('正在获取弹幕...');
        @header("refresh:1;url=./do.php?step=3.5");
    } elseif($_GET['step']==3.5){
        checkRefer();
        downloadCommentForRoot($GLOBALS['video_root_path']);
        @header("refresh:1;url=./do.php?step=4");
    } elseif($_GET['step']==4){
        //checkRefer();
        mkContainer('<a id="cttx">任务完成</a></br></br> <div style="text-align: center"> <a id="jump" href="./do.php" id="cttx">重新执行</a><a>&#12288&#12288</a><a id="jump" href="./" id="cttx">返回主页</a> </div>');
    } 
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="./css/icon.png" type="image/x-icon">
        <title>执行任务中...</title>
        <style>
            html {
                padding: 50px 10px;
                font-size: 16px;
                line-height: 1.4;
                color: #666;
                background: #F6F6F3;
                -webkit-text-size-adjust: 100%;
                -ms-text-size-adjust: 100%;
            }

            html,
            input { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; }
            body {
                max-width: 500px;
                _width: 500px;
                padding: 30px 20px;
                margin: 0 auto;
                background: #FFF;
            }
            ul {
                padding: 0 0 0 40px;
            }
            .container {
                max-width: 380px;
                _width: 380px;
                margin: 0 auto;
            }
            #cttx {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
        </style>
    </head>
    <body>
        <?php doTask(); ?>
    </body>
</html>


