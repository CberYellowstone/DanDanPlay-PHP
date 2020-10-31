<?php
global $video_root_path;
global $data_path;
global $version;
global $web_users;
global $able_cache;
global $able_webp;
$video_root_path=dirname(__FILE__).'/video';
$data_path=dirname(__FILE__).'/data';
$version = "Alpha 0.2.0";

//用户设置
$site_name = "Yellowstone's Anime Site";//站点名称
$remote_port = "8009";//远程访问端口号,需为http协议,留空取访问时端口号
$remote_addres = "apps.ystone.top";//留空则为访问时地址
$authorization = FALSE;
$root_username = "Yellowstone";//随便填
$web_users=array('Yellowstone' => '12345678','user2' => 'password',);//账号,密码
$api_authkey = "";
$able_cache = TRUE;
$able_webp = TRUE; //是否启用webp格式图片,启用后将获得更好性能但在某些老旧浏览器上将无法正常显示


//其他设置
$DanmakuArea = "83%";
$DanmakuDurationCss = "danmaku 9s linear";
$About_link = "https://github.com/CberYellowstone/DanDanPlay-PHP";


if(!$remote_addres){$remote_addres = $_SERVER['SERVER_NAME'];}
if(!$remote_port){$remote_port = $_SERVER["SERVER_PORT"];}

?>