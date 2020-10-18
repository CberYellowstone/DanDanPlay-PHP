<?php
include_once '../../function.php';


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
        $video_file_name = str_replace('\\','\\\\',getFileName($each_video_path));
        $video_file_size = filesize($each_video_path);
        $video_time = getVideoTime($each_video_path,TRUE)[0];
        $video_information_list = readVideoInformation($each_video_path,TRUE)[0];
        //print_r(getVideoInformation($each_video_path)[1].'</br>');
        $animeTitle = removeQuote($video_information_list['animeTitle']);
        $episodeTitle = removeQuote($video_information_list['episodeTitle']);
        $animeId = $video_information_list['animeId'];
        $episodeId = $video_information_list['episodeId'];
        $video_path = $video_information_list['file_path'];
        $video_parent_path_md5 = md5(getFileName(dirname($video_path),TRUE));
        $video_file_md5 = md5(getFileName($video_path));
        $video_path = str_replace('\\','\\\\',$video_path);
        //$last_time = readLastTime($each_video_path);
        //echo ($video_file_md5."</br>");
        echo ('{"AnimeId":'.$animeId.',"EpisodeId":'.$episodeId.',"AnimeTitle":"'.$animeTitle.'","EpisodeTitle":"'.$episodeTitle.'","Id":"'.$video_parent_path_md5."-".$video_file_md5.'","Hash":"'.$video_parent_path_md5."-".$video_file_md5.'","Name":"'.$video_file_name.'","Path":"'.$video_path.'","Size":'.$video_file_size.',"Rate":0,"IsStandalone":false,"Created":"2020-10-15T13:03:41.5584929+08:00","LastMatch":"2020-10-15T13:04:29.9537029+08:00","LastPlay":null,"LastThumbnail":"2020-10-15T13:04:29.9761506+08:00","Duration":'.$video_time.'}');
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


function sendVideoPicFromMD5($md5){
    $parent_md5 = explode("-",$md5)[0];
    $video_md5 = explode("-",$md5)[1];
    @ header("Content-Type:image/png");
    echo (file_get_contents(($GLOBALS['data_path'].'/'.$parent_md5.'/'.$video_md5.'/'.$video_md5.'.jpg')));
}


function sendVideoFileFromMD5($md5){
    $video_path = readVideoInformationFromMD5($md5)[0]['file_path'];
    $video_url = 'http://'.$_SERVER['HTTP_HOST'].'/'.getFileName($GLOBALS['video_root_path'],TRUE).'/'.getFileName(dirname($video_path,1),TRUE).'/'.getFileName($video_path,TRUE);
    //$video_url = $GLOBALS['video_root_path'].'/'.getFileName(dirname($video_path,1),TRUE).'/'.getFileName($video_path,TRUE);
    header("Location:$video_url");
}



if($_GET['action']=='library'){
    mkJsonIndexForRoot($GLOBALS['video_root_path']);

} elseif($_GET['action']=='image'){
    sendVideoPicFromMD5($_GET['id']);
    
} elseif($_GET['action']=='stream'){
    sendVideoFileFromMD5($_GET['id']);

} elseif($_GET['action']=='comment'){
    sendCommentFromMD5($_GET['id']);
}
