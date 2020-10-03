# DanDanPlay-PHP

基于 PHP 重构的 [弹弹Play远程访问首页](https://github.com/kaedei/dandanplay-libraryindex)（媒体库内容的展示以及视频播放还有弹幕）

用于**服务器**部署，已在 Centos 上通过测试

需求 PHP 7+ ，个人是 PHP7.4 ，理论上更低版本也行，自行尝试

需要安装 FFmpeg ，默认路径为 /usr/bin/ffmpeg ，可自行更改

用到了 [DanDanPlay API](https://api.acplay.net/swagger/ui/index#/) ，弹幕会比电脑端少一丢丢，但基本一致(和手机端理论一致)

## 已知问题

>* ~~有的番剧弹幕会报错，没空修，有愿意接坑的可以试试接屎山~~(已解决: 2020年10月3日)
>* 发布的弹幕并不会同步到服务器，不打算在短期内修复了，不怎么影响使用

## 说明

>* 访问 do.php 可以实现刷新主页，添加新番之后一定要访问一次 do.php (可以添加到 crontab )
>* 里面还有部分的测试用语句，不影响性能和使用，介意的话自己删掉就行
>
>
>>番剧文件夹需放在 /video 目录下，可用软链接，但是 链接/目录名 要和标准中文译名(大概)一致(可从 弹弹Play 复制)
>>
>>番剧目录下不可有**非视频文件**，且番剧视频文件名要能被识别 (罗马字标题或者中文译名)
>>
>>如果识别错误可以手动去 /data 目录下更改相应 json ，具体方法自行摸索

---

## 多平台

* [GitHub](https://github.com/CberYellowstone/DanDanPlay-PHP)
* [Gitee](https://gitee.com/Yellowstone/DanDanPlay-PHP)
* [腾讯工蜂](https://git.code.tencent.com/Yellowstone/DanDanPlay-PHP)

## DEMO
[个人demo](https://apps.ystone.top:488/ddp/)~~~(见已知问题)~~~

## 目前使用的第三方组件

* [DPlayer](https://github.com/MoePlayer/DPlayer)
* [jQuery](https://github.com/jquery/jquery)
* [FFmpeg](https://github.com/FFmpeg/FFmpeg)

## 感谢
>感谢 [@kaedei](https://github.com/kaedei) 大佬的指教， 使我修完了最后的屎山bug