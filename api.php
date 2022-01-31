<?php
define('IN_SYS', TRUE);
include_once 'function.php';
@header("Cache-Control: no-cache, must-revalidate");


function checkAuth($auth_key,$needKey){
    if($needKey and !$auth_key==$GLOBALS['api_authkey']){
        exit;
    }
}

function xmlFormat($str){
$str = str_replace("&", "&amp;", $str);
$str = str_replace("<", "&lt;", $str);
$str = str_replace(">", "&gt;", $str);
$str = str_replace("'", "&apos;", $str);
$str = str_replace("/", '&quot;', $str);
return $str;
}

function sendCommentFromMD5($md5){
    $video_information_list = readVideoInformationFromMD5($md5)[0];
    $episodeId = $video_information_list['episodeId'];
    $parent_md5 = explode("-",$md5)[0];
    $video_md5 = explode("-",$md5)[1];
    $comment_list = (json_decode(file_get_contents($GLOBALS['data_path'].'/'.$parent_md5.'/'.$video_md5.'/'.$episodeId.'.json'),TRUE));
    $comment_xml_text = "";
    $comment_xml_text = $comment_xml_text.'<?xml version="1.0"?><i xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><chatserver>chat.bilibili.com</chatserver><chatid>10000</chatid><mission>0</mission><maxlimit>8000</maxlimit><source>e-r</source><ds>931869000</ds><de>937654881</de><max_count>8000</max_count>';
    foreach($comment_list['comments'] as $each_in_list){
        $p_list = explode(',',$each_in_list['p']);
        $p0 = $p_list[0];
        $p1 = $p_list[1];
        $p2 = $p_list[2];
        $p3 = $p_list[3];
        $cid = $each_in_list['cid'];
        $comment =  xmlFormat($each_in_list['m']);
        //$comment_xml_text = $comment_xml_text."[".$p0.",".$p1.",".$p2.',"'.$p3.'","'.$comment.'"],';
        $comment_xml_text = $comment_xml_text.'<d p="'.$p0.','.$p1.',25,'.$p2.','.$cid.',0,0,0">'.$comment.'</d>';
    }
    //$comment_xml_text = rtrim($comment_xml_text, ",");
    $comment_xml_text = $comment_xml_text."</i>";
    $comment_xml_text = str_replace(array("\r\n", "\r", "\n"), "", $comment_xml_text);
    header('Content-Type:application/xml; charset=utf-8');
    echo($comment_xml_text);
}


function mkJsonIndexForFolder($folder_path){
    $i=0;
    foreach(countFolder($folder_path)[1] as $each_video_path){
        if($i){echo(",");}else{$i = $i + 1;}
        //$video_pic_link = getVideoPic($each_video_path,TRUE);
        $video_file_name = str_replace('\\','\\\\',getFileName($each_video_path,FALSE,TRUE));
        $video_file_size = filesize($each_video_path);
        $video_time = getVideoTime($each_video_path,TRUE)[0];
        $video_information_list = readVideoInformation($each_video_path,TRUE)[0];
        //print_r(getVideoInformation($each_video_path)[1].'</br>');
        $animeTitle = removeQuote($video_information_list['animeTitle']);
        $episodeTitle = removeQuote($video_information_list['episodeTitle']);
        $episodeTitle = str_replace(['第1话','第2话','第3话','第4话','第5话','第6话','第7话','第8话','第9话'],['第01话','第02话','第03话','第04话','第05话','第06话','第07话','第08话','第09话'],$episodeTitle);
        $animeId = $video_information_list['animeId'];
        $episodeId = $video_information_list['episodeId'];
        $video_path = $video_information_list['file_path'];
        $video_parent_path_md5 = md5(getFileName(dirname($video_path),TRUE));
        $video_file_md5 = md5(getFileName($video_path));
        $video_path = str_replace($GLOBALS['video_root_path'],'','A:'.$video_path);
        $video_path = str_replace('/','\\',$video_path);
        $video_path = str_replace('\\','\\\\',$video_path);
        //$last_time = readLastTime($each_video_path);
        //echo ($video_file_md5."</br>");
        echo ('{"AnimeId":'.$animeId.',"EpisodeId":'.$episodeId.',"AnimeTitle":"'.$animeTitle.'","EpisodeTitle":"'.$episodeTitle.'","Id":"'.$video_parent_path_md5."-".$video_file_md5.'","Hash":"'.$video_file_md5.'","Name":"'.$video_file_name.'","Path":"'.$video_path.'","Size":'.$video_file_size.',"Rate":0,"IsStandalone":false,"Created":"2020-10-15T13:03:41.5584929+08:00","LastMatch":"2020-10-15T13:04:29.9537029+08:00","LastPlay":null,"LastThumbnail":"2020-10-15T13:04:29.9761506+08:00","Duration":'.$video_time.'}');
    }
}

function mkJsonIndexForRoot($root){
    echo ("[");
    $j = 0;
    foreach(listRoot($root,FALSE) as $each_in_root_mix){
        if($j){echo(",");}else{$j = $j + 1;}
        mkJsonIndexForFolder($each_in_root_mix);    
    }
    echo ("]");
}

function showImg($img){
    $info = getimagesize($img);
    $imgExt = image_type_to_extension($info[2], false); //获取文件后缀
    $fun = "imagecreatefrom{$imgExt}";
    $imgInfo = $fun($img);         //1.由文件或 URL 创建一个新图象。如:imagecreatefrompng ( string $filename )
    //$mime = $info['mime'];
    $mime = image_type_to_mime_type(exif_imagetype($img)); //获取图片的 MIME 类型
    header('Content-Type:'.$mime);
    $quality = 100;
    if($imgExt == 'png') $quality = 9;   //输出质量,JPEG格式(0-100),PNG格式(0-9)
    $getImgInfo = "image{$imgExt}";
    $getImgInfo($imgInfo, null, $quality); //2.将图像输出到浏览器或文件。如: imagepng ( resource $image )
    imagedestroy($imgInfo);
}

function sendVideoPicFromMD5($md5){
    $parent_md5 = explode("-",$md5)[0];
    $video_md5 = explode("-",$md5)[1];
    if($GLOBALS['able_webp']){
        showImg(($GLOBALS['data_path'].'/'.$parent_md5.'/'.$video_md5.'/'.$video_md5.'.webp'));
    } else {
        showImg(($GLOBALS['data_path'].'/'.$parent_md5.'/'.$video_md5.'/'.$video_md5.'.jpg'));
    }
}

function sendVideoFile($filePath){
    $filename = basename($filePath);
    header("Content-type: application/octet-stream");
    // 处理中文文件名
    $ua = $_SERVER["HTTP_USER_AGENT"];
    $encoded_filename = rawurlencode($filename);
    if (preg_match("/MSIE/", $ua)) {
        header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
    } else if (preg_match("/Firefox/", $ua)) {
        header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
    } else {
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    }
    // 让 Xsendfile 发送文件
    header("X-Sendfile: $filePath");
    
}

function sendVideoFileFromMD5($md5){
    $video_path = readVideoInformationFromMD5($md5)[0]['file_path'];
    sendVideoFile($video_path);
}

function allowCross(){
    header('Content-Type: text/html;charset=utf-8');
    header('Access-Control-Allow-Origin:'.$_SERVER["HTTP_ORIGIN"]); // *代表允许任何网址请求
    header('Access-Control-Allow-Methods:*'); // 允许请求的类型
    header('Access-Control-Allow-Credentials: true'); // 设置是否允许发送 cookies
    header('Access-Control-Allow-Headers:*');
}

function responseOptions(){
    if($_SERVER["REQUEST_METHOD"] == "OPTIONS"){
        exit(0);}}

function mkconfFromMD5($md5){
    $video_information_array = readVideoInformationFromMD5($md5)[0];
    $video_animeId = $video_information_array['animeId'];
    $video_episodeId = $video_information_array['episodeId'];
    $animeTitle = removeQuote($video_information_array['animeTitle']);
    $episodeTitle = removeQuote($video_information_array['episodeTitle']);
    $video_path = $video_information_array['file_path'];
    $video_name = getFileName($video_path);
    $video_folder_path = dirname($video_path,1);
    $video_array = array('AnimeId' => (int)$video_animeId, 'EpisodeId' => (int)$video_episodeId, 'AnimeTitle' => $animeTitle, 'EpisodeTitle' => $episodeTitle, 'Id' => $md5, 'Hash' => '', 'Name' => $video_name, 'Path' => $video_path, 'Size' => 0, 'IsStandalone' => FALSE, 'Created' => '', 'LastMatch' => '', 'LastPlay' => null, 'LastThumbnail' => '', 'Duration' => 0);
    $video_url = '/api/v1/stream/id/'.$md5;
    $image_url = '/api/v1/image/id/'.$md5;
    $vtt_url = '/api/v1/subtitle/vtt/id/'.$md5;
    $color = '#000000';
    $dmDuration = '9s';
    $dmSize = '25px';

    // return;
    $videoFiles_array = array();
    foreach(countFolder($video_folder_path)[1] as $each_path){
        $video_name_all = getFileName($each_path,TRUE);
        $each_episodeTitle = removeQuote(readVideoInformation($each_path)[0]['episodeTitle']);
        $video_parent_path_md5 = md5(getFileName(dirname($each_path,1),TRUE));
        $video_file_md5 = md5(getFileName($each_path));
        $video_id = $video_parent_path_md5."-".$video_file_md5;
        $isCurrent = ($video_id == $md5 ? TRUE : FALSE);
        $temp_array = ['id'=>$video_id, 'episodeTitle'=>$each_episodeTitle, 'fileName'=>$video_name_all, 'isCurrent'=>$isCurrent];
        $videoFiles_array[] = $temp_array;
    }

    $out_array = array('id'=>$md5, 'video'=>$video_array, 'videoUrl'=>$video_url, 'imageUrl'=>$image_url, 'vttUrl'=>$vtt_url, 'color'=>$color, 'dmDuration'=>$dmDuration, 'dmSize'=>$dmSize, 'dmArea'=>$GLOBALS['DanmakuArea'], 'videoFiles'=>$videoFiles_array);
    echo(json_encode($out_array));

}

allowCross();
responseOptions();
switch($_GET['action']){
    case "welcome":
        header('Content-Type:application/json; charset=utf-8');
        echo(json_encode(array('message'=>"Hello DanDanPlay-PHP!", 'version'=>$GLOBALS['version'], 'time'=>date('Y-m-d H:i:s', time()), 'tokenRequired'=>FALSE)));
        break;
    case "auth":
        $auth_list = json_decode(file_get_contents("php://input"),TRUE);
        if(isset($auth_list['userName']) && isset($auth_list['password'])){
            if(checkUserAndPassword($auth_list['userName'],$auth_list['password'])){
                echo(json_encode(array('id'=>'88888888-4444-4444-4444-121212121212', 'userName'=>$auth_list['userName'], 'token'=>$GLOBALS['api_authkey'], 'error'=>'')));
            }else{
                echo(json_encode(array('id'=>'', 'userName'=>'', 'token'=>'', 'error'=>'用户名或密码错误')));
            }}else{
            echo(json_encode(array('id'=>'', 'userName'=>'', 'token'=>'', 'error'=>'用户名或密码缺失')));}
        break;
    case "library":
        header('Content-Type:application/json; charset=utf-8');
        checkAuth($_SERVER['HTTP_AUTHORIZATION'],$authorization);
        mkCache(0);
        mkJsonIndexForRoot($GLOBALS['video_root_path']);
        mkCache(1);
        break;
    case "image":
        sendVideoPicFromMD5($_GET['id']);
        break;
    case "stream":
        sendVideoFileFromMD5($_GET['id']);
        saveLastTime($_GET['id']);
        break;
    case "comment":
        sendCommentFromMD5($_GET['id']);
        break;
    case "playerconfig":
        mkconfFromMD5($_GET['id']);
        break;
    case "comment_json":
        getCommentFromMD5($_GET['id']);
        break;    
    case "gettest":
        // phpinfo();
        break;    
    case "clcache":
        $filename = md5("/api/v1/library");
        $fileabs = dirname(__FILE__,3).'/cache/'.$filename;
        echo($fileabs);
        unlink($fileabs);
        break;
    default:
        sendStatusCode(403,"Forbidden");
}



?>