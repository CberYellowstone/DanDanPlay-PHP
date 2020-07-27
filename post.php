<?php
include 'function.php';

$file_name = '[KxIX]Shuumatsu Nani Shitemasuka Isogashii Desuka Sukutte Moratte Ii Desuka 12[GB][1080P]';
$file_hash = 'c72962cd1cbe70821e5731b48e350479';
$file_size = '436509756';
$vedio_duration = '1450';
$match_mode = 'hashAndFileName';


(exec("curl -X POST --header 'Content-Type: text/xml' --header 'Accept: text/json' -d '<?xml version=\"1.0\"?> <MatchRequest> <fileName>".$file_name."</fileName> <fileHash>".$file_hash."</fileHash> <fileSize>".$file_size."</fileSize> <videoDuration>".$vedio_duration."</videoDuration> <matchMode>".$match_mode."</matchMode> </MatchRequest>' 'https://api.acplay.net/api/v2/match'"));

$path_fot_test = "/mnt/usb/[KxIX]Shuumatsu Nani Shitemasuka Isogashii Desuka Sukutte Moratte Ii Desuka[GB][1080P]/[KxIX]Shuumatsu Nani Shitemasuka Isogashii Desuka Sukutte Moratte Ii Desuka 12[GB][1080P].mp4";


function getVedioInformation2($file_path){
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
    preg_match_all('/\"animeTitle\":(.*?),/', $post_result, $animeTitle_matches);
    $animeTitle_list = $animeTitle_matches[1];
    $animeTitle_first = $animeTitle_list[0];
    preg_match_all('/\"episodeTitle\":(.*?),/', $post_result, $episodeTitle_matches);
    $episodeTitle_list = $episodeTitle_matches[1];
    $episodeTitle_first = $episodeTitle_list[0];
    //echo ($post_result);
    return array($post_result,array($episodeId_first,$animeId_first,$animeTitle_first,$episodeTitle_first));
}


//getVedioInformation2($path_fot_test);
print_r(getVedioInformation2($path_fot_test)[1]);

?>