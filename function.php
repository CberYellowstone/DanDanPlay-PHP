<?php  
global $video_root_path;
global $data_path;
global $version;
$video_root_path=dirname(__FILE__).'/video';
$data_path=dirname(__FILE__).'/data';

$site_name = "Yellowstone's Anime Site";
$version = "Alpha 0.0.1";
$DanmakuArea = "83%";
$DanmakuDurationCss = "danmaku 9s linear";
$About_link = "https://github.com/CberYellowstone/DanDanPlay-PHP";

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
        if($_GET['animeName'] && $isOutPut) {
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
        return (array_slice(explode('.',(end(explode('/',$file_path)))),-2,1))[0];
    }
    elseif($IsFolder){
        return end(explode('/',rtrim($file_path,'/')));
    }
}

function formatSpace($need_format_str){
    return str_replace(" ","\ ",$need_format_str);
}

//$video_file,$pic_name均为为完整带路径文件名
function mkpic($video_file,$video_time,$pic_name,$pic_size) {
    $video_file = formatSpace($video_file);
    $mkpic_command = "/usr/bin/ffmpeg -ss ".$video_time." -i ".$video_file." -y -f mjpeg -t 1 -s ".$pic_size." ".$pic_name;
    if(!isExists(dirname($pic_name,1))){
        mkdir(iconv("UTF-8", "GBK", (dirname($pic_name,1))),0777,true); 
    }       
    //echo($mkpic_command."</br>");
    if(!isExists($pic_name)){
        //echo ($mkpic_command."</br>");
        system($mkpic_command);
    }
}

//$mkpic_folder带路径
function mkpicForFolder($mkpic_folder){
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
        mkpic($mkpic_video,289,($save_path."/".$folder_name."/".$video_name."/".$video_name.".jpg"),'400*225');
    }
}

//带路径
function getVideoPic($file_path,$auto_mk=FALSE){
    $video_name_md5 = md5(getFileName($file_path));
    $folder_name = getFileName(dirname($file_path,1),TRUE);
    $mkpic_pic_path = $GLOBALS['data_path']."/".md5($folder_name)."/".$video_name_md5."/".$video_name_md5.".jpg";
    if(!isExists($GLOBALS['data_path']."/".md5($folder_name)."/".$video_name_md5."/".$video_name_md5.".jpg") && $auto_mk){
        mkpic($GLOBALS['video_root_path']."/".$folder_name."/".getFileName($file_path),290,$mkpic_pic_path,'400*225');
    }
    return ("./".getFileName($GLOBALS['data_path'],TRUE)."/".md5($folder_name)."/".$video_name_md5."/".$video_name_md5.".jpg");
}

function getVideoPicFromMD5($md5){
    $parent_md5 = explode("-",$md5)[0];
    $video_md5 = explode("-",$md5)[1];
    return ('./'.getFileName($GLOBALS['data_path'],TRUE).'/'.$parent_md5.'/'.$video_md5.'/'.$video_md5.'.jpg');
}

//带路径
function getFileMD5($file_path){
    return (hash_file('md5',$file_path));
}

function echoServerInformation(){
    date_default_timezone_set("Asia/Shanghai");
    echo "DanDanPlay-PHP版本：".$GLOBALS['version']." | 服务器PHP版本：".PHP_VERSION." | 当前服务器时间: ".date('Y/m/d H:i:s', time());
}

function getVideoTime($file_path,$isOutSecond=FALSE){
    $file_path = formatSpace($file_path);
    $video_time = exec ("ffmpeg -i ".$file_path." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");// 总长度
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
    return array($post_result,array($episodeId_first,$animeId_first,$animeTitle_first,$episodeTitle_first));
}

function saveVideoInformationForFolder($get_information_folder,$Force_make=FALSE){
    foreach((countFolder($get_information_folder)[1]) as $get_information_video){
        saveVideoInformation($get_information_video,$Force_make);
    }
}

function saveVideoInformationForRoot($root,$Force_make){
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
    }elseif(isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json')){
        //echo (readVideoInformation($file_path)[1]).'</br>';
        //print_r(readVideoInformation($file_path)[0]['file_path']);
        //echo ("</br>");
    } 
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

function mkCardForFolder($folder_path){
    foreach(countFolder($folder_path)[1] as $each_video_path){
        $video_pic_link = getVideoPic($each_video_path,TRUE);
        $video_file_name = getFileName($each_video_path);
        $video_file_size = getFileSize($each_video_path);
        $video_time = getVideoTime($each_video_path)[0];
        $video_information_list = readVideoInformation($each_video_path,TRUE)[0];
        //print_r(getVideoInformation($each_video_path)[1].'</br>');
        $animeTitle = removeQuote($video_information_list['animeTitle']);
        $episodeTitle = removeQuote($video_information_list['episodeTitle']);
        $video_path = $video_information_list['file_path'];
        $video_parent_path_md5 = md5(getFileName(dirname($video_path),TRUE));
        $video_file_md5 = md5(getFileName($video_path));
        $last_time = readLastTime($each_video_path);
        //echo ($video_file_md5."</br>");
        echo ('<div class="col-sm-6 col-md-4 float-left pt-4"><div class="card"><a href="./video.php?video='.$video_parent_path_md5."-".$video_file_md5.'"><img class="card-img-top" src="'.$video_pic_link.'" alt="Card image cap"></a><div class="card-body"><h5 class="card-title line-limit-length"><a href="./index.php?animeName='.$animeTitle.'">'.$animeTitle.'</a></h5><h5 class="card-title" style="overflow: hidden; white-space: nowrap;text-overflow: ellipsis"></h5><p class="video-text line-limit-length"><a href="./video.php?video='.$video_parent_path_md5."-".$video_file_md5.'">'.$episodeTitle.'</a><br>'.$video_file_name.'<br>时长：'.$video_time.'<br>文件体积：'.$video_file_size.'<br>上次播放：'.$last_time.'</p></div></div></div>');
    }
}

function mkCardForRoot($root,$point=""){
    if(!$point){
        foreach(listRoot($root,FALSE) as $each_in_root_mix){
            mkCardForFolder($each_in_root_mix);
        }    
    } else {
        mkCardForFolder($root.'/'.$point);
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

function getCommentFromMD5($md5){
    $video_information_list = readVideoInformationFromMD5($md5)[0];
    $episodeId = $video_information_list['episodeId'];
    $parent_md5 = explode("-",$md5)[0];
    $video_md5 = explode("-",$md5)[1];
    $comment_list = (json_decode(file_get_contents($GLOBALS['data_path'].'/'.$parent_md5.'/'.$video_md5.'/'.$episodeId.'.json'),TRUE));
    $comment_test = "";
    $comment_test = $comment_test.'{"code":0,"data":[';
    foreach($comment_list['comments'] as $each_in_list){
        $p_list = explode(',',$each_in_list['p']);
        $p0 = $p_list[0];
        $p1 = $p_list[1];
        if($p1 == "1"){$p1 = "0";}
        $p2 = $p_list[2];
        $p3 = $p_list[3];
        $comment_test = $comment_test."[".$p0.",".$p1.",".$p2.',"'.$p3.'","'.$each_in_list['m'].'"],';
    }
    $comment_test = rtrim($comment_test, ",");
    $comment_test = $comment_test."]}";
    echo($comment_test);
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

$path_fot_test = "/var/www/html/ddp/video/末日时在做什么？有没有空？可以来拯救吗？/[KxIX]Shuumatsu Nani Shitemasuka Isogashii Desuka Sukutte Moratte Ii Desuka 11[GB][1080P].mp4";


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
elseif($_GET['action']=='test'){
    echo ("这是一个测试接口!</br>");
    //saveLastTime('3443dab0dcee781a18e927eb92a50740-e14822833a7c329477d6867001f241c2');
    echo ("</br>输出结束!</br>");
}



?>