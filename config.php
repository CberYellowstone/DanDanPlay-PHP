<?php
global $video_root_path, $data_path, $version, $web_users, $able_cache, $able_webp, $cache_limit, $api_authkey, $remote_port, $remote_addres, $convert_trad_to_simpl, $DanmakuArea;
$video_root_path=dirname(__FILE__).'/video';
$data_path=dirname(__FILE__).'/data';
$version = "Alpha 0.4.3";
$About_link = "https://github.com/CberYellowstone/DanDanPlay-PHP";


//用户设置
$site_name = "Yellowstone's Anime Site"; //站点名称
$remote_port = "8009"; //远程访问端口号,需为http协议,留空取访问时端口号
$remote_addres = "apps.ystone.top"; //留空则为访问时地址
$authorization = FALSE; //网页是否需要登录、远程访问是否需要密钥
$root_username = "Yellowstone"; //随便填,没啥意义(出现在远程访问二维码中)
$web_users=array('Yellowstone' => '12345678','user2' => 'password',);//网页用户的账号,密码
$api_authkey = "12345678"; //远程访问密钥
$able_cache = TRUE; //是否启用缓存
$cache_limit = 60 * 24 * 7; //缓存过期时间,单位为分钟
$able_webp = TRUE; //是否启用webp格式图片,启用后将获得更好性能但在某些老旧浏览器上将无法正常显示
$convert_trad_to_simpl = TRUE; //是否启用弹幕繁体转简体

//其他设置
$DanmakuArea = "83%";
$DanmakuDurationCss = "danmaku 9s linear";
ini_set('date.timezone','Asia/Shanghai'); //时区设置,若出现时间异常可以考虑注释掉这行

if(!$remote_addres){$remote_addres = $_SERVER['SERVER_NAME'];}
if(!$remote_port){$remote_port = $_SERVER["SERVER_PORT"];}
if(!$authorization){$web_users['ANONYMOUS'] = 'WJpKk233ctLyIGTWSPtYDrTK5rCBXZft';}
?>