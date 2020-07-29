<?php  
global $vedio_root_path;
global $data_path;
global $version;
$vedio_root_path=dirname(__FILE__).'/vedio';
$data_path=dirname(__FILE__).'/data';

$site_name = "Yellowstone's Anime Site";
$version = "Alpha 0.0.1";


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
function listRoot($root,$isOutPut=TRUE){
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
                echo ("<a href=\"./index.php?animeName=$each_in_root\" class=\"list-group-item list-group-item-action rounded-0 line-limit-length\"><span class=\"badge badge-primary\">$each_folder_count</span> $each_in_root</a>");
            }
        }
    }
    return $root_list;
    //$root_list内容包含路径
}

//$folder带路径
function countFolder($folder){
    $all_in_folder = scandir($folder);
    $count_in_folder = 0;
    $vedio_list = array();
    $vedio_name_list = array();
    foreach($all_in_folder as $each_in_folder){
        $each_in_folder_mix = $folder.'/'.$each_in_folder;
        if(!is_dir($each_in_folder_mix)){
            $count_in_folder += 1;
            $vedio_list[] = $each_in_folder_mix;
            $vedio_name_list[] = $each_in_folder;
        }
    }
    return array($count_in_folder,$vedio_list,$vedio_name_list);
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

//$vedio_file,$pic_name均为为完整带路径文件名
function mkpic($vedio_file,$vedio_time,$pic_name,$pic_size) {
    $vedio_file = formatSpace($vedio_file);
    $mkpic_command = "/usr/bin/ffmpeg -ss ".$vedio_time." -i ".$vedio_file." -y -f mjpeg -t 1 -s ".$pic_size." ".$pic_name;
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
    foreach((countFolder($mkpic_folder))[1] as $mkpic_vedio){
        $vedio_name = md5(getFileName($mkpic_vedio));
        //echo ($save_path."/".$folder_name."/".$vedio_name."</br>");
        if(!isExists($save_path."/".$folder_name."/".$vedio_name)){
            mkdir(iconv("UTF-8", "GBK", ($save_path."/".$folder_name."/".$vedio_name)),0777,true); 
        }       
        //$vedio_name = formatSpace($vedio_name);
        mkpic($mkpic_vedio,289,($save_path."/".$folder_name."/".$vedio_name."/".$vedio_name.".jpg"),'400*225');
    }
}

//带路径
function getVedioPic($file_path,$auto_mk=FALSE){
    $vedio_name_md5 = md5(getFileName($file_path));
    $folder_name = getFileName(dirname($file_path,1),TRUE);
    $mkpic_pic_path = $GLOBALS['data_path']."/".md5($folder_name)."/".$vedio_name_md5."/".$vedio_name_md5.".jpg";
    if(!isExists($GLOBALS['data_path']."/".md5($folder_name)."/".$vedio_name_md5."/".$vedio_name_md5.".jpg") && $auto_mk){
        mkpic($GLOBALS['vedio_root_path']."/".$folder_name."/".getFileName($file_path),290,$mkpic_pic_path,'400*225');
    }
    return ("./".getFileName($GLOBALS['data_path'],TRUE)."/".md5($folder_name)."/".$vedio_name_md5."/".$vedio_name_md5.".jpg");
}

//带路径
function getFileMD5($file_path){
    return (hash_file('md5',$file_path));
}

function echoServerInformation(){
    date_default_timezone_set("Asia/Shanghai");
    echo "DanDanPlay-PHP版本：".$GLOBALS['version']." | 服务器PHP版本：".PHP_VERSION." | 当前服务器时间: ".date('Y/m/d H:i:s', time());
}

function getVedioTime($file_path,$isOutSecond=FALSE){
    $file_path = formatSpace($file_path);
    $vedio_time = exec ("ffmpeg -i ".$file_path." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");// 总长度
    $vedio_time = explode(':',explode('.',$vedio_time)[0]);
    $vedio_time = $vedio_time[1].":".$vedio_time[2];
    if($isOutSecond){
        $vedio_time = explode(':',$vedio_time);
        $vedio_time = $vedio_time[0]*60 + $vedio_time[1];
    }
    $vedio_create_time = date ("Y-m-d H:i:s",filectime ($file_path));// 创建时间
    return array($vedio_time,$vedio_create_time);
    }


function getVedioInformation($file_path){
    $file_name = getFileName($file_path);
    $file_hash = getFileMD5($file_path);
    $file_size = filesize($file_path);
    $vedio_duration = getVedioTime($file_path,TRUE)[0];
    $match_mode = 'hashAndFileName';
    $post_result = exec("curl -X POST --header 'Content-Type: text/xml' --header 'Accept: text/json' -d '<?xml version=\"1.0\"?> <MatchRequest> <fileName>".$file_name."</fileName> <fileHash>".$file_hash."</fileHash> <fileSize>".$file_size."</fileSize> <videoDuration>".$vedio_duration."</videoDuration> <matchMode>".$match_mode."</matchMode> </MatchRequest>' 'https://api.acplay.net/api/v2/match'");
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

function saveVedioInformationForFolder($get_information_folder){
    foreach((countFolder($get_information_folder)[1]) as $get_information_vedio){
        saveVedioInformation($get_information_vedio);
    }
}


function saveVedioInformation($file_path){
    $folder_name = md5(getFileName(getFileName(dirname($file_path,1),TRUE)));
    $file_name = md5(getFileName($file_path));
    if(!isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json')){
        $vedio_information_list = getVedioInformation($file_path)[1];
        $vedio_information_list_named = array('episodeId' => $vedio_information_list[0], 'animeId' => $vedio_information_list[1], 'animeTitle' => $vedio_information_list[2], 'episodeTitle' => $vedio_information_list[3]);
        $vedio_information_json = json_encode($vedio_information_list_named,JSON_UNESCAPED_UNICODE);
        //echo ($vedio_information_json);
        //echo ($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json');
        if(!isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name)){
            mkdir(iconv("UTF-8", "GBK", ($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name)),0777,true); 
        }   
        echo($vedio_information_json.'</br>');    
        file_put_contents($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json', $vedio_information_json);    
    }elseif(isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json')){
        echo (readVedioInformation($file_path)[1]).'</br>';
    } 
}

function readVedioInformation($file_path,$auto_get=FALSE){
    $folder_name = md5(getFileName(getFileName(dirname($file_path,1),TRUE)));
    $file_name = md5(getFileName($file_path));
    if(!isExists($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json')){
        saveVedioInformation($file_path);
    }
    $vedio_information_json = file_get_contents($GLOBALS['data_path'].'/'.$folder_name.'/'.$file_name.'/'.$file_name.'.json');
    $vedio_information_list = json_decode($vedio_information_json,TRUE);
    return array($vedio_information_list,$vedio_information_json);
}

function mkCardForFolder($folder_path){
    foreach(countFolder($folder_path)[1] as $each_vedio_path){
        $vedio_pic_link = getVedioPic($each_vedio_path,TRUE);
        $vedio_file_name = getFileName($each_vedio_path);
        $vedio_file_size = getFileSize($each_vedio_path);
        $vedio_time = getVedioTime($each_vedio_path)[0];
        $vedio_information_list = readVedioInformation($each_vedio_path,TRUE)[0];
        //print_r(getVedioInformation($each_vedio_path)[1].'</br>');
        $animeTitle = removeQuote($vedio_information_list['animeTitle']);
        $episodeTitle = removeQuote($vedio_information_list['episodeTitle']);
        //echo ($animeTitle."  ".$episodeTitle);
        echo ('<div class="col-sm-6 col-md-4 float-left pt-4"><div class="card"><a href="./index.php?'.$animeTitle.'"><img class="card-img-top" src="'.$vedio_pic_link.'" alt="Card image cap"></a><div class="card-body"><h5 class="card-title line-limit-length"><a href="/index.html?animeId=@Current.AnimeId">'.$animeTitle.'</a></h5><h5 class="card-title" style="overflow: hidden; white-space: nowrap;text-overflow: ellipsis"></h5><p class="video-text line-limit-length"><a href="/web/@Current.Id">'.$episodeTitle.'</a><br>'.$vedio_file_name.'<br>时长：'.$vedio_time.'<br>文件体积：'.$vedio_file_size.'<br>上次播放：@Current.LastPlay</p></div></div></div>');
    }
}

function mkCardForRoot($root){
    foreach(listRoot($root,FALSE) as $each_in_root_mix){
        mkCardForFolder($each_in_root_mix);
    }
}

$path_fot_test = "/var/www/html/ddp/vedio/末日时在做什么？有没有空？可以来拯救吗？/[KxIX]Shuumatsu Nani Shitemasuka Isogashii Desuka Sukutte Moratte Ii Desuka 11[GB][1080P].mp4";


if($_GET['action']=='listRoot'){
    listRoot($vedio_root_path);
}
elseif($_GET['action']=='mkpic'){
    mkpicForFolder(listRoot($vedio_root_path,FALSE)[0]);
}
elseif($_GET['action']=='getFileName'){ 
    echo(getFileName($path_fot_test,TRUE));
}
elseif($_GET['action']=='getVedioPic'){
    echo (getVedioPic($path_fot_test));
}
elseif($_GET['action']=='test'){
    echo ("这是一个测试接口!</br>");
    mkCardForRoot($vedio_root_path);
    echo ("</br>输出结束!</br>");
}



?>