<?php  
global $vedio_root_path;
global $data_path;
$vedio_root_path=dirname(__FILE__).'/vedio';
$data_path=dirname(__FILE__).'/data';



function isCil(){
    return preg_match("/cli/i", php_sapi_name()) ? 1 : 0;
}

function isExists($test_path){
    if(is_dir($test_path)){
        echo $test_path.": dir</br>";
        return TRUE;
    }
    elseif(file_exists($test_path)){
        echo $test_path.": file</br>";
        return TRUE;
    }
    elseif(!is_dir($test_path) || !file_exists($test_path)){
        echo $test_path.": not exists</br>";
        return FALSE;
    }
}

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
}

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

function mkpic($vedio_file,$vedio_time,$pic_name,$pic_size) {
    if (empty ($vedio_time))$vedio_time = '1';// 默认截取第一秒第一帧
    $vedio_file = formatSpace($vedio_file);
    $mkpic_command = "/usr/bin/ffmpeg -ss ".$vedio_time." -i ".$vedio_file." -y -f mjpeg -t 1 -s ".$pic_size." ".$pic_name;
    //echo($mkpic_command."</br>");
    if(!isExists($pic_name)){
        //echo ($mkpic_command."</br>");
        system($mkpic_command);
    }
}

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
        mkpic($mkpic_vedio,290,($save_path."/".$folder_name."/".$vedio_name."/".$vedio_name.".jpg"),'400*225');
    }
}

function getVedioPic($folder_name,$vedio_name,$auto_mk=FALSE){
    $vedio_name_md5 = md5(getFileName($vedio_name));
    $mkpic_pic_path = $GLOBALS['data_path']."/".md5($folder_name)."/".$vedio_name_md5."/".$vedio_name_md5.".jpg";
    if(!isExists($GLOBALS['data_path']."/".md5($folder_name)."/".$vedio_name_md5."/".$vedio_name_md5.".jpg") && $auto_mk){
        mkpic($GLOBALS['vedio_root_path']."/".$folder_name."/".$vedio_name,290,$mkpic_pic_path,'400*225');
    }
    return ("./".getFileName($GLOBALS['data_path'],TRUE)."/".md5($folder_name)."/".$vedio_name_md5."/".$vedio_name_md5.".jpg</br>");
}

function getFileSize($file_path){
    echo filesize($file_path);
}



if($_GET['action']=='listRoot'){
    listRoot($vedio_root_path);
}
elseif($_GET['action']=='mkpic'){
    mkpicForFolder(listRoot($vedio_root_path,FALSE)[0]);
}
elseif($_GET['action']=='getFileName'){
    echo(getFileName('/mnt/usb/[KxIX]Shuumatsu\ Nani\ Shitemasuka\ Isogashii\ Desuka\ Sukutte\ Moratte\ Ii\ Desuka[GB][1080P]',TRUE));
}
elseif($_GET['action']=='getVedioPic'){
    echo (getVedioPic(getFileName(listRoot($vedio_root_path,FALSE)[0],TRUE),countFolder(listRoot($vedio_root_path,FALSE)[0])[2][0],TRUE));
}
elseif($_GET['action']=='test'){
    echo ("这是一个测试接口!");
}



?>