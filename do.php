<?php
include_once 'function.php';
@header("Cache-Control: no-cache, must-revalidate");

function checkRefer($source=0){
    if(!$_SERVER['HTTP_REFERER']){
        //@header("refresh:1;url=./do.php");
        @header('Location: ./do.php');
    }
}

if(!$_GET['step']){
    echo('正在准备执行任务');
    @header("refresh:1;url=./do.php?step=1");
} elseif($_GET['step']==1){
    checkRefer();
    echo('正在生成视频缩略图...');
    @header("refresh:1;url=./do.php?step=1.5");
} elseif($_GET['step']==1.5){
    checkRefer();
    mkpicForRoot($video_root_path);
    @header("refresh:1;url=./do.php?step=2");
} elseif($_GET['step']==2){
    checkRefer();
    echo('正在识别番剧...');
    @header("refresh:1;url=./do.php?step=2.5");
} elseif($_GET['step']==2.5){
    checkRefer();
    saveVideoInformationForRoot($video_root_path);
    @header("refresh:1;url=./do.php?step=3");
} elseif($_GET['step']==3){
    checkRefer();
    echo('正在获取弹幕...');
    @header("refresh:1;url=./do.php?step=3.5");
} elseif($_GET['step']==3.5){
    checkRefer();
    downloadCommentForRoot($video_root_path);
    @header("refresh:1;url=./do.php?step=4");
} elseif($_GET['step']==4){
    checkRefer();
    echo('任务完成');
} 


exit();
mkpicForRoot($video_root_path);
saveVideoInformationForRoot($video_root_path);
downloadCommentForRoot($video_root_path);

echo("任务完成");



?>