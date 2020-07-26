<?php  

$file=dirname(__FILE__).'/vedio';

function list_root($root){
    $all_in_root = scandir($root);
    foreach($all_in_root as $each_in_root){
        $each_in_root_mix = $root.'/'.$each_in_root;
        if(is_dir($each_in_root_mix)){
            if($each_in_root=='.' || $each_in_root=='..'){
                continue;
            }
            $each_folder_count = count_folder($each_in_root_mix);
            echo ("<a href=\"./index.html?animeId=@Current.AnimeId\" class=\"list-group-item list-group-item-action rounded-0 line-limit-length\"><span class=\"badge badge-primary\">$each_folder_count</span> $each_in_root</a>");
        }
    }
}

function count_folder($folder){
    $all_in_folder = scandir($folder);
    $count_in_folder = 0;
    foreach($all_in_folder as $each_in_folder){
        $each_in_folder_mix = $folder.'/'.$each_in_folder;
        if(!is_dir($each_in_folder_mix)){
            $count_in_folder += 1;
        }
    }
    return $count_in_folder;
}





list_root($file);


?>