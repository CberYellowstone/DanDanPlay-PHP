<?php

$file_name = '_KxIX_Shuumatsu_Nani_Shitemasuka_Isogashii_Desuka_Sukutte_Moratte_Ii_Desuka_04_GB__1080P_';
$file_hash = 'FF14BA068501FF6873DC497DD54F4B94';
$file_size = '390868395';
$vedio_duration = '1490';
$match_mode = 'hashAndFileName';


echo (exec("curl -X POST --header 'Content-Type: text/xml' --header 'Accept: text/json' -d '<?xml version=\"1.0\"?> <MatchRequest> <fileName>".$file_name."</fileName> <fileHash>".$file_hash."</fileHash> <fileSize>".$file_size."</fileSize> <videoDuration>".$vedio_duration."</videoDuration> <matchMode>".$match_mode."</matchMode> </MatchRequest>' 'https://api.acplay.net/api/v2/match'"));



?>