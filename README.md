# DanDanPlay-PHP

基于PHP重构的 [弹弹Play远程访问首页](https://github.com/kaedei/dandanplay-libraryindex)（媒体库内容的展示以及视频播放还有弹幕）

用于服务器部署,已在Centos上通过测试

PHP需求版本不知道,个人是PHP7.4

需要安装FFmpeg,默认路径为/usr/bin/ffmpeg,可自行更改

用到了[DanDanPlay API](https://api.acplay.net/swagger/ui/index#/),弹幕会比电脑端少一些,但基本一致(和手机端理论一致)

## 说明

* 主页是index.php,function.php里面是所有功能组件
* 访问do.php可以实现刷新主页,添加新番之后一定要访问一次do.php(可以添加到crontab)
* 里面还有部分的测试用语句,不影响性能和使用,介意的话自己删掉就行

番剧文件夹需放在video目录下,可用软链接,但是链接/目录名要和标准中文译名一致(可从弹弹Play复制)

番剧目录下不可有非视频文件,且番剧视频文件名要能被识别(罗马字标题或者中文译名)

如果识别错误可以手动去data目录下更改相应json,具体方法自行摸索

---

## 多平台

* [GitHub](https://github.com/CberYellowstone/DanDanPlay-PHP)
* [Gitee](https://gitee.com/Yellowstone/DanDanPlay-PHP)
* [腾讯工蜂](https://git.code.tencent.com/Yellowstone/DanDanPlay-PHP)

## DEMO
[个人demo](https://apps.ystone.top:488/ddp/)(暂时只做到主页面)

## 目前使用的第三方组件

* [DPlayer](https://github.com/MoePlayer/DPlayer)
* jQuery
* FFmpeg