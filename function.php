<?php

use sqhlib\Hanzi\HanziConvert;
include_once 'zh-t2s/HanziConvert.php';
include_once 'config.php';
error_reporting(0);

function isCil(){
    return preg_match("/cli/i", php_sapi_name()) ? 1 : 0;
}

function removeQuote($str) {
    if (preg_match("/^\"/",$str)){
        $str = substr($str, 1, strlen($str) - 1);
    }
    //判断字符串是否以'"'结束
    if (preg_match("/\"$/",$str)){
        $str = substr($str, 0, strlen($str) - 1);;
    }
    return $str;
}

function urljsonDecode($value) { 
    $escapers = array("\\", "/", '"', "\n", "\r", "\t", "\x08", "\x0c", "'");
    $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b", "%27");
    $result = str_replace($escapers, $replacements, $value);
    return $result;
}

function getFileSize($file_path){
    $file_size = filesize($file_path);
    $KB = 1024;$MB = 1024 * $KB;$GB = 1024 * $MB;$TB = 1024 * $GB;
    if ($file_size < $KB) {
        return $file_size."B";
    } elseif ($file_size<$MB) {
        return round($file_size/$KB,1)."KB";
    } elseif ($file_size<$GB) {
        return round($file_size/$MB,1)."MB";
    } elseif ($file_size<$TB) {
        return round($file_size/$GB,1)."GB";
    } else {
        return round($file_size/$TB,1)."TB";
    }
}

function isExists($test_path,$isOutPut=FALSE){
    if(is_dir($test_path)){
        if($isOutPut){
            echo $test_path.": dir</br>";
        }
        return TRUE;
    }elseif(file_exists($test_path)){
        if($isOutPut){
            echo $test_path.": file</br>";
        }
        return TRUE;
    }elseif(!is_dir($test_path) || !file_exists($test_path)){
        if($isOutPut){
            echo $test_path.": not exists</br>";
        }
        return FALSE;
    }
}

//$root带路径
function listRoot($root,$isOutPut=TRUE,$point=''){
    $root_list = array();
    $all_in_root = scandir($root);
    foreach($all_in_root as $each_in_root){
        $each_in_root_mix = $root.'/'.$each_in_root;
        if(is_dir($each_in_root_mix)){
            if($each_in_root=='.' || $each_in_root=='..'){
                continue;
            }
            $each_folder_count = countFolder($each_in_root_mix)[0];
            $root_list[] = $each_in_root_mix;
            if($isOutPut){
                if(!$point){//这里写得不好,有点耗资源了,先这样吧,就一点点而已
                    echo ("<a href=\"./index.php?animeName=".$each_in_root."\" class=\"list-group-item list-group-item-action rounded-0 line-limit-length\"><span class=\"badge badge-primary\">".$each_folder_count."</span> ".$each_in_root."</a>");
                }
                }
            }
        }
        if(!isCil() && $isOutPut && $_GET['animeName']) {
            $count_point = countFolder($root.'/'.$point)[0];
            echo ("<a href=\"./index.php?animeName=".$point."\" class=\"list-group-item list-group-item-action rounded-0 line-limit-length\"><span class=\"badge badge-primary\">".$count_point."</span> ".$point."</a>");
        }
    return $root_list;
    //$root_list内容包含路径
}

//$folder带路径
function countFolder($folder){
    $all_in_folder = scandir($folder);
    $count_in_folder = 0;
    $video_list = array();
    $video_name_list = array();
    foreach($all_in_folder as $each_in_folder){
        $each_in_folder_mix = $folder.'/'.$each_in_folder;
        if(!is_dir($each_in_folder_mix)){
            $count_in_folder += 1;
            $video_list[] = $each_in_folder_mix;
            $video_name_list[] = $each_in_folder;
        }
    }
    return array($count_in_folder,$video_list,$video_name_list);
}

function getFileName($file_path,$IsFolder=FALSE){
    if(!$IsFolder){
        $file_full_name = end(explode('/',rtrim($file_path,'/')));
        return str_replace(strrchr($file_full_name, "."),"",$file_full_name);
    }
    elseif($IsFolder){
        return end(explode('/',rtrim($file_path,'/')));
    }
}

function formatSpace($need_format_str){
    return str_replace(" ","\ ",$need_format_str);
}

//$video_file,$pic_name均为为完整带路径文件名
function mkpic($video_file,$video_time,$pic_name,$pic_size,$isFouce=FALSE) {
    $video_file = formatSpace($video_file);
    $mkpic_command = "/usr/bin/ffmpeg -loglevel quiet -ss ".$video_time." -i ".$video_file." -y -f mjpeg -t 1 -r 1 -s ".$pic_size." ".$pic_name;
    if($GLOBALS['able_webp']){
        $mkpic_command = "/usr/bin/ffmpeg -loglevel quiet -ss ".$video_time." -i ".$video_file." -y -f webp -t 1 -r 1 -s ".$pic_size." ".$pic_name;
    }
    if(!isExists(dirname($pic_name,1))){
        mkdir(iconv("UTF-8", "GBK", (dirname($pic_name,1))),0777,true); 
    }       
    //echo($mkpic_command."</br>");
    if(!isExists($pic_name or $isFouce)){
        //echo ($mkpic_command."</br>");
        system($mkpic_command);
    }
}

//$mkpic_folder带路径
function mkpicForFolder($mkpic_folder,$isFouce=FALSE){
    $save_path=$GLOBALS['data_path'];
    //echo ($save_path."</br>");
    $folder_name = md5(getFileName($mkpic_folder,TRUE));
    foreach((countFolder($mkpic_folder))[1] as $mkpic_video){
        $video_name = md5(getFileName($mkpic_video));
        //echo ($save_path."/".$folder_name."/".$video_name."</br>");
        if(!isExists($save_path."/".$folder_name."/".$video_name)){
            mkdir(iconv("UTF-8", "GBK", ($save_path."/".$folder_name."/".$video_name)),0777,true); 
        }       
        //$video_name = formatSpace($video_name);
        if($GLOBALS['able_webp']){
            mkpic($mkpic_video,289,($save_path."/".$folder_name."/".$video_name."/".$video_name.".webp"),'400*225',$isFouce);
        } else{
            mkpic($mkpic_video,289,($save_path."/".$folder_name."/".$video_name."/".$video_name.".jpg"),'400*225',$isFouce);
        }
    }
}

function mkpicForRoot($root,$isFouce=FALSE){
    foreach(listRoot($root,FALSE) as $each_in_root_mix){
        mkpicForFolder($each_in_root_mix,$isFouce);
    }    
}

//带路径
function getVideoPic($file_path,$auto_mk=FALSE){
    $video_name_md5 = md5(getFileName($file_path));
    $folder_name = getFileName(dirname($file_path,1),TRUE);
    if($GLOBALS['able_webp']){
        $mkpic_pic_path = $GLOBALS['data_path']."/".md5($folder_name)."/".$video_name_md5."/".$video_name_md5.".webp";
    } else {
        $mkpic_pic_path = $GLOBALS['data_path']."/".md5($folder_name)."/".$video_name_md5."/".$video_name_md5.".jpg";
    }
    if(!isExists($mkpic_pic_path) && $auto_mk){
        mkpic($GLOBALS['video_root_path']."/".$folder_name."/".getFileName($file_path),290,$mkpic_pic_path,'400*225');
    }
    if($GLOBALS['able_webp']){
        return ("./".getFileName($GLOBALS['data_path'],TRUE)."/".md5($folder_name)."/".$video_name_md5."/".$video_name_md5.".webp");
    } else {
        return ("./".getFileName($GLOBALS['data_path'],TRUE)."/".md5($folder_name)."/".$video_name_md5."/".$video_name_md5.".jpg");
    }
}

function getVideoPicFromMD5($md5){
    $parent_md5 = explode("-",$md5)[0];
    $video_md5 = explode("-",$md5)[1];
    if($GLOBALS['able_webp']){
        return ('./'.getFileName($GLOBALS['data_path'],TRUE).'/'.$parent_md5.'/'.$video_md5.'/'.$video_md5.'.webp');
    } else {
        return ('./'.getFileName($GLOBALS['data_path'],TRUE).'/'.$parent_md5.'/'.$video_md5.'/'.$video_md5.'.jpg');
    }
}

//带路径
function getFileMD5($file_path){
    return (hash_file('md5',$file_path));
}

function echoServerInformation(){
    date_default_timezone_set("Asia/Shanghai");
    if($GLOBALS['able_cache']){
        echo "DanDanPlay-PHP版本：".$GLOBALS['version']." | 服务器PHP版本：".PHP_VERSION." | 缓存文件日期: ".date('Y/m/d H:i:s', time());
    } else {
        echo "DanDanPlay-PHP版本：".$GLOBALS['version']." | 服务器PHP版本：".PHP_VERSION." | 当前服务器时间: ".date('Y/m/d H:i:s', time());
    }
}

function getVideoTime($file_path,$isOutSecond=FALSE){
    $file_path = formatSpace($file_path);
    $video_time = exec ("ffmpeg -loglevel quiet -i ".$file_path." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");// 总长度
    $video_time = explode(':',explode('.',$video_time)[0]);
    $video_time = $video_time[1].":".$video_time[2];
    if($isOutSecond){
        $video_time = explode(':',$video_time);
        $video_time = $video_time[0]*60 + $video_time[1];
    }
    $video_create_time = date ("Y-m-d H:i:s",filectime ($file_path));// 创建时间
    return array($video_time,$video_create_time);
    }


function getVideoInformation($file_path){
    $animeTitleFromDirName = getFileName(dirname($file_path),TRUE);
    $file_name = getFileName($file_path);
    $file_hash = getFileMD5($file_path);
    $file_size = filesize($file_path);
    $video_duration = getVideoTime($file_path,TRUE)[0];
    $match_mode = 'hashAndFileName';
    $post_result = exec("curl -X POST --header 'Content-Type: text/xml' --header 'Accept: text/json' -d '<?xml version=\"1.0\"?> <MatchRequest> <fileName>".$file_name."</fileName> <fileHash>".$file_hash."</fileHash> <fileSize>".$file_size."</fileSize> <videoDuration>".$video_duration."</videoDuration> <matchMode>".$match_mode."</matchMode> </MatchRequest>' 'https://api.acplay.net/api/v2/match'");
    preg_match_all('/\"episodeId\":(.*?),/', $post_result, $episodeId_matches);
    $episodeId_list = $episodeId_matches[1];
    $episodeId_first = $episodeId_list[0];
    preg_match_all('/\"animeId\":(.*?),/', $post_result, $animeId_matches);
    $animeId_list = $animeId_matches[1];
    $animeId_first = $animeId_list[0];
    preg_match_all('/\"animeTitle\":(.*?),\"/', $post_result, $animeTitle_matches);
    $animeTitle_list = $animeTitle_matches[1];
    $animeTitle_first = $animeTitle_list[0];
    preg_match_all('/\"episodeTitle\":(.*?),\"/', $post_result, $episodeTitle_matches);
    $episodeTitle_list = $episodeTitle_matches[1];
    $episodeTitle_first = $episodeTitle_list[0];
    //echo ($post_result);
    //print_r(array($episodeId_first,$animeId_first,$animeTitle_first,$episodeTitle_first));
    $i = 0;
    foreach($animeTitle_list as $animeTitle_each){
        if($animeTitle_each == '"'.$animeTitleFromDirName.'"'){
            $animeTitle_first = $animeTitle_each;
            $episodeId_first = $episodeId_list[$i];
            $animeId_first = $animeId_list[$i];
            $episodeTitle_first = $episodeTitle_list[$i];
        }
        $i = $i + 1;
    }
    return array($post_result,array($episodeId_first,$animeId_first,$animeTitle_first,$episodeTitle_first));
}

function saveVideoInformationForFolder($get_information_folder,$Force_make=FALSE){
    foreach((countFolder($get_information_folder)[1]) as $get_information_video){
        saveVideoInformation($get_information_video,$Force_make);
    }
}

function saveVideoInformationForRoot($root,$Force_make=FALSE){
    foreach(listRoot($root,FALSE) as $each_in_root_mix){
        saveVideoInformationForFolder($each_in_root_mix,$Force_make);
    }    
}

function saveVideoInformation($file_path,$Force_make=FALSE){
    $folder_name = md5(getFileName(getFileName(dirname($file_path,1),TRUE)));
    $file_name = md5(getFileName($file_path));
    if(!isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json') or $Force_make){
        $video_information_list = getVideoInformation($file_path)[1];
        $video_information_list_named = array('episodeId' => $video_information_list[0], 'animeId' => $video_information_list[1], 'animeTitle' => $video_information_list[2], 'episodeTitle' => $video_information_list[3], 'file_path' => $file_path);
        $video_information_json = json_encode($video_information_list_named,JSON_UNESCAPED_UNICODE);
        //echo ($video_information_json);
        //echo ($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json');
        if(!isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name)){
            mkdir(iconv("UTF-8", "GBK", ($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name)),0777,true); 
        }   
        //echo($video_information_json.'</br>');    
        file_put_contents($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json', $video_information_json);    
    }//elseif(isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json')){
        //echo (readVideoInformation($file_path)[1]).'</br>';
        //print_r(readVideoInformation($file_path)[0]['file_path']);
        //echo ("</br>");
    //} 
}

function readVideoInformation($file_path,$auto_get=FALSE){
    $folder_name = md5(getFileName(getFileName(dirname($file_path,1),TRUE)));
    $file_name = md5(getFileName($file_path));
    if(!isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json')){
        saveVideoInformation($file_path);
    }
    $video_information_json = file_get_contents($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json');
    $video_information_list = json_decode($video_information_json,TRUE);
    return array($video_information_list,$video_information_json);
}

function readVideoInformationFromMD5($md5){
    $parent_md5 = explode("-",$md5)[0];
    $video_md5 = explode("-",$md5)[1];
    $video_information_json = file_get_contents($GLOBALS['data_path'].'/'.$parent_md5.'/'.$video_md5.'/'.$video_md5.'.json');
    $video_information_list = json_decode($video_information_json,TRUE);
    return array($video_information_list,$video_information_json);
}

function mkCardForFolder($folder_path,$isSreaching=''){
    foreach(countFolder($folder_path)[1] as $each_video_path){
        $video_pic_link = getVideoPic($each_video_path,TRUE);
        $video_file_name = getFileName($each_video_path);
        $video_file_size = getFileSize($each_video_path);
        $video_time = getVideoTime($each_video_path)[0];
        $video_information_list = readVideoInformation($each_video_path,TRUE)[0];
        //print_r(getVideoInformation($each_video_path)[1].'</br>');
        $animeTitle = removeQuote($video_information_list['animeTitle']);
        $episodeTitle = removeQuote($video_information_list['episodeTitle']);
        if($isSreaching && !(strstr((HanziConvert::convert($video_file_name.$animeTitle.$episodeTitle)),$isSreaching))){
            //echo $video_file_name.$animeTitle.$episodeTitle.'</br>';
            continue;
        }
        $video_path = $video_information_list['file_path'];
        $video_parent_path_md5 = md5(getFileName(dirname($video_path),TRUE));
        $video_file_md5 = md5(getFileName($video_path));
        $last_time = readLastTime($each_video_path);
        //echo ($video_file_md5."</br>");
        echo ('<div class="col-sm-6 col-md-4 float-left pt-4"><div class="card"><a href="./video.php?video='.$video_parent_path_md5."-".$video_file_md5.'"><img class="card-img-top" src="'.$video_pic_link.'" alt="Card image cap"></a><div class="card-body"><h5 class="card-title line-limit-length"><a href="./index.php?animeName='.$animeTitle.'">'.$animeTitle.'</a></h5><h5 class="card-title" style="overflow: hidden; white-space: nowrap;text-overflow: ellipsis"></h5><p class="video-text line-limit-length"><a href="./video.php?video='.$video_parent_path_md5."-".$video_file_md5.'">'.$episodeTitle.'</a><br>'.$video_file_name.'<br>时长：'.$video_time.'<br>文件体积：'.$video_file_size.'<br>上次播放：'.$last_time.'</p></div></div></div>');
    }
}

function mkCardForRoot($root,$point="",$isSreaching=''){
    if(!$point){
        foreach(listRoot($root,FALSE) as $each_in_root_mix){
            mkCardForFolder($each_in_root_mix,$isSreaching);
        }    
    } else {
        mkCardForFolder($root.'/'.$point,$isSreaching);
    }
}

function getVideoFileFromMD5($md5){
    $video_path = readVideoInformationFromMD5($md5)[0]['file_path'];
    $video_url = './'.getFileName($GLOBALS['video_root_path'],TRUE).'/'.getFileName(dirname($video_path,1),TRUE).'/'.getFileName($video_path,TRUE);
    return ($video_url);
}

function downloadComment($file_path,$Force_downlaod=FALSE){
    $video_information_list = readVideoInformation($file_path)[0];
    $episodeId = $video_information_list['episodeId'];
    $folder_name = md5(getFileName(getFileName(dirname($file_path,1),TRUE)));
    $file_name = md5(getFileName($file_path));
    if(!isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$episodeId.'.json') or $Force_downlaod){
        exec("wget -O ".$GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$episodeId.'.json'." https://api.acplay.net/api/v2/comment/".$episodeId."?withRelated=true");
    }
}

function downloadCommentForFolder($folder_path,$Force_downlaod=FALSE){
    foreach((countFolder($folder_path)[1]) as $file_path){
        downloadComment($file_path,$Force_downlaod);
    }
}

function downloadCommentForRoot($root,$Force_downlaod=FALSE){
    foreach(listRoot($root,FALSE) as $each_in_root_mix){
        downloadCommentForFolder($each_in_root_mix);
    }
}
function filterUtf8($string){
    if($string){
    //先把正常的utf8替换成英文逗号
    $result = preg_replace('%(
    [\x09\x0A\x0D\x20-\x7E]
    | [\xC2-\xDF][\x80-\xBF]
    | \xE0[\xA0-\xBF][\x80-\xBF]
    | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
    | \xED[\x80-\x9F][\x80-\xBF]
    | \xF0[\x90-\xBF][\x80-\xBF]{2}
    | [\xF1-\xF3][\x80-\xBF]{3}
    | \xF4[\x80-\x8F][\x80-\xBF]{2}
    )%xs',',',$string);
    //转成字符数字
    $charArr = explode(',', $result);
    //过滤空值、重复值以及重新索引排序
    $findArr = array_values(array_flip(array_flip(array_filter($charArr))));
    return $findArr ? str_replace($findArr, "", $string) : $string;
    }
    return $string;
}

function is_utf8($string) {
    return preg_match('%^(?:
    [\x09\x0A\x0D\x20-\x7E]
    | [\xC2-\xDF][\x80-\xBF]
    | \xE0[\xA0-\xBF][\x80-\xBF]
    | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}
    | \xED[\x80-\x9F][\x80-\xBF]
    | \xF0[\x90-\xBF][\x80-\xBF]{2}
    | [\xF1-\xF3][\x80-\xBF]{3}
    | \xF4[\x80-\x8F][\x80-\xBF]{2}
    )*$%xs', $string);
}
function getCommentFromMD5($md5){
    $video_information_list = readVideoInformationFromMD5($md5)[0];
    $episodeId = $video_information_list['episodeId'];
    $parent_md5 = explode("-",$md5)[0];
    $video_md5 = explode("-",$md5)[1];
    $comment_list = (json_decode(file_get_contents($GLOBALS['data_path'].'/'.$parent_md5.'/'.$video_md5.'/'.$episodeId.'.json'),TRUE));
    $comment_text = "";
    $comment_text = $comment_text.'{"code":0,"data":[';
    foreach($comment_list['comments'] as $each_in_list){
        $p_list = explode(',',$each_in_list['p']);
        $p0 = $p_list[0];
        $p1 = $p_list[1];
        $p2 = $p_list[2];
        $p3 = $p_list[3];
        $comment =  $each_in_list['m'];
        $comment = str_replace('\\','\\\\',$comment);
        $comment = str_replace('"','\"',$comment);
        $comment_text = $comment_text."[".$p0.",".$p1.",".$p2.',"'.$p3.'","'.$comment.'"],';
    }
    $comment_text = rtrim($comment_text, ",");
    $comment_text = $comment_text."]}";
    $comment_text = str_replace(array("\r\n", "\r", "\n", "	"), "", $comment_text);
    header('Content-Type:application/json; charset=utf-8');
    echo $comment_text = is_utf8($comment_text) ? $comment_text : filterUtf8($comment_text);
    // echo($comment_text);
}

function mkList($folder_path,$now_path=""){
    foreach(countFolder($folder_path)[1] as $each_path){
        $video_name_all = getFileName($each_path,TRUE);
        //echo($video_name_all);
        $episodeTitle = removeQuote(readVideoInformation($each_path)[0]['episodeTitle']);
        $video_parent_path_md5 = md5(getFileName(dirname($each_path,1),TRUE));
        $video_file_md5 = md5(getFileName($each_path));
        $video_url = './video.php?video='.$video_parent_path_md5."-".$video_file_md5;
        echo('<a href="'.$video_url.'" class="list-group-item list-group-item-action ');
        if($now_path == $each_path){
            echo('active');
        }
        echo('">'.$episodeTitle.'<small>'.$video_name_all.'</small></a>');
        //echo('</br>');
    }
}


function mkListFromMD5($md5){
    $video_now_path = readVideoInformationFromMD5($md5)[0]['file_path'];
    $video_folder = dirname($video_now_path,1);
    mkList($video_folder,$video_now_path);
}

function saveLastTime($md5){
    $parent_md5 = explode("-",$md5)[0];
    $video_md5 = explode("-",$md5)[1];
    $last_time_json_path = $GLOBALS['data_path'].'/'.$parent_md5.'/'.$video_md5.'/last_time.json';
    date_default_timezone_set("Asia/Shanghai");
    $time_now = date('Y/m/d H:i:s', time());
    file_put_contents($last_time_json_path,json_encode(array('last_time' => $time_now)));
}

function readLastTime($file_path){
    $folder_name = md5(getFileName(getFileName(dirname($file_path,1),TRUE)));
    $file_name = md5(getFileName($file_path));
    if(!isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/last_time.json')){
        return("无");
    }
    $last_time_json = file_get_contents($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/last_time.json');
    $last_time = json_decode($last_time_json,TRUE)['last_time'];
    return $last_time;
}

function sendStatusCode($code,$message,$jumpURL="",$contentLength=-1){
    @header($_SERVER["SERVER_PROTOCOL"]." ".$code." ".$message);//不区分协议1.1 /1.0
    @header("Status: ".$code." ".$message);
    http_response_code($code);
    $_SERVER['REDIRECT_STATUS'] = $code;
    if($jumpURL){
        @header('location:'.$jumpURL);
    }
    if($contentLength!=-1){
        @header('Content-Length: '.$contentLength);
    }
}

function getIfHTTPS(){
    if($_SERVER['HTTPS']=='on'){
        return 'https';
    }else{
        return 'http';
    }
}

function mkCache($part){
    if($part==0) {
        $filename = md5($_SERVER['REQUEST_URI']);
        $fileabs = dirname(__FILE__).'/cache/'.$filename;
        //查找有没有缓存文件的存在
        if(!$_POST and $GLOBALS['able_cache']) {
            if(file_exists($fileabs)) {
                $now_time = time();
                $last_time = filemtime($fileabs);
                if(($now_time-$last_time) / 60 > $GLOBALS['cache_limit']) {
                    unlink($fileabs);
                } else {include $fileabs;exit;}
            } ob_start();
        }
    } elseif($part==1) {
        if(!$_POST and $GLOBALS['able_cache']){
            $filename = md5($_SERVER['REQUEST_URI']);
            $fileabs = dirname(__FILE__).'/cache/'.$filename;    
            $content = ob_get_contents();
            $fp = fopen($fileabs, 'wb+');
            fwrite($fp, $content);fclose($fp);
            ob_flush(); //从PHP内存中释放出来缓存（取出数据）
            flush(); //把释放的数据发送到浏览器显示
            ob_end_clean(); //清空缓冲区的内容并关闭这个缓冲区
        }
    }
}

function clCache($uri){
    if($_POST['clCache']){
        @header("Cache-Control: no-cache, must-revalidate");
        $filename = md5($uri);
        $fileabs = dirname(__FILE__).'/cache/'.$filename;
        unlink($fileabs);
        @header('Location: '.$_SERVER['REQUEST_URI']);
    }
}

function checkUserAndPassword($web_username,$web_password){
    if(array_key_exists($web_username,$GLOBALS['web_users']) and $GLOBALS['web_users'][$web_username]==$web_password){
        return TRUE;
    }
}

function checkUserAndPasswordFromCookie($cookieWebUserName,$cookieAuth){
    if(array_key_exists($cookieWebUserName,$GLOBALS['web_users']) and hash('sha256',$GLOBALS['web_users'][$cookieWebUserName])==$cookieAuth){
        return TRUE;
    }
}



if($_GET['action']=='listRoot'){
    //listRoot($video_root_path);
}
elseif($_GET['action']=='mkpic'){
    //mkpicForFolder(listRoot($video_root_path,FALSE)[0]);
}
elseif($_GET['action']=='getFileName'){ 
    //echo(getFileName($path_fot_test,TRUE));
}
elseif($_GET['action']=='getVideoPic'){
    //echo (getVideoPic($path_fot_test));
}
elseif($_GET['action']=='saveVideoInformation'){
    //saveVideoInformationForFolder(listRoot($video_root_path,FALSE)[0],FALSE);
}
elseif($_GET['action']=='getCommentFromMD5'){
    getCommentFromMD5($_GET['md5']);
}
elseif($_GET['action']=='clCache'){
    @header("Location: ./");
}
elseif($_GET['action']=='test'){
    echo ("这是一个测试接口!</br>");
    //saveLastTime('3443dab0dcee781a18e927eb92a50740-e14822833a7c329477d6867001f241c2');
    echo ("</br>输出结束!</br>");
}



?>