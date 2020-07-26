<?php


function getVideoCover($file,$time,$name,$size) {
    if (empty ($time))$time = '1';// 默认截取第一秒第一帧
    $strlen = strlen($file);
    // $videoCover = substr($file,0,$strlen-4);
    // $videoCoverName = $videoCover.'.jpg';// 缩略图命名
    //exec("ffmpeg -i ".$file." -y -f mjpeg -ss ".$time." -t 0.001 -s 320x240 ".$name."",$out,$status);
    $str = "/usr/bin/ffmpeg -ss ".$time." -i ".$file." -y -f mjpeg -t 1 -s ".$size." ".$name;
    //echo $str."</br>";
    system($str);
}

getVideoCover("/mnt/usb/[KxIX]Shuumatsu\ Nani\ Shitemasuka\ Isogashii\ Desuka\ Sukutte\ Moratte\ Ii\ Desuka[GB][1080P]/[KxIX]Shuumatsu\ Nani\ Shitemasuka\ Isogashii\ Desuka\ Sukutte\ Moratte\ Ii\ Desuka\ 01[GB][1080P].mp4",290,'/var/www/html/ddp/write/1.jpg','400*225');



?>