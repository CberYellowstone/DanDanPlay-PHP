# DanDanPlay-PHP

基于 PHP 重构的 [弹弹Play远程访问首页](https://github.com/kaedei/dandanplay-libraryindex)（包含 媒体库内容的展示、视频播放、弹幕以及远程访问）

用于 **基于的Linux的服务器** 部署，已在 `Centos 7` 上通过测试

（主要是 `NAS` 上面，你要是 `WindowsServer` 就直接用 `弹弹Play桌面版` 就行了）

PHP版本要求： `PHP 7+` ，个人部署环境是 `PHP7.4` ，理论上更低版本也行，请自行尝试

另外需要安装 FFmpeg ，默认路径为 `/usr/bin/ffmpeg` ，可自行更改

用到了 [DanDanPlay API](https://api.acplay.net/swagger/ui/index#/) ，弹幕会比电脑端少一丢丢，但基本一致(和手机端理论一致)

---

## 说明
> 
>  
>* 访问 `do.php` 可以实现刷新主页，添加新番之后需要访问一次 `do.php` (可以添加到 crontab )
>* 里面还有部分的测试用语句，不影响性能和使用，介意的话自己删掉就行
>
>
>---
>* 目录结构
>>配置在 `config.php` 以及 `/api/v1/config.php` 中,使用前请自行更改相关参数
>>番剧文件夹需放在 /video 目录下，可用软链接，但是 链接/目录名 要和标准中文译名(完全)一致(可从 弹弹Play 复制)
>>
>>番剧目录下不可有**非视频文件**，且番剧视频文件名要能被识别 (罗马字标题或者中文译名)
>>
>>如果识别错误可以手动去 `/data` 目录下更改相应 json ，具体方法自行摸索(懒得写了)
> 
>---
> 
>* 远程访问功能:
>>需开启Apache的rewrite功能，方法请自行百度，已经内置`.htaccess`文件。
>>
>>nginx用户请自己摸索适配（我相信你们都是大佬）
>>
>> 弹弹Play概念版APP 并**不能**支持HTTPS协议，所以 远程访问 需使用单独的Http端口
>> 
>>**同时，弹弹Play概念版APP的播放器内核需更改为`EXO Player`，否则会报错**
>>
>>此外，远程访问 所使用的域名需占用整个域名，不得安装在二级目录下
>>>例如：主站用 `https://xxx.xxx.xxx/ddp` 是可以的
>>>
>>>但是， 远程访问的api地址必须是像 `http://xxx.xxx.xxx/` 这样的
>
>---
>* 搜索功能:
>>
>>目前繁简体以及和制汉字之间的互相转换有一些问题，暂时不解决（DanDanPlay自己都没解决这个问题）
>>以及左边的番剧列表并不会更新,完全不影响使用, 所以就不改了

---

## 已知问题

>* ~~识别准确率有待提升，已经想到了解决方法，近期修复~~(已解决：2020年10月25日，识别方法更多依赖文件夹名)
>* ~~有的番剧弹幕会报错，没空修，有愿意接坑的可以试试接屎山~~(已解决: 2020年10月3日)
>* 发布的弹幕并不会同步到服务器，不打算在短期内修复了，不怎么影响使用

---

## 多平台

* [GitHub](https://github.com/CberYellowstone/DanDanPlay-PHP)
* [Gitee](https://gitee.com/Yellowstone/DanDanPlay-PHP)
* [腾讯工蜂](https://git.code.tencent.com/Yellowstone/DanDanPlay-PHP)

---

## DEMO
[个人demo](https://apps.ystone.top:488/ddp/)~~(见已知问题)~~

远程访问demo: 地址: `apps.ystone.top` , 端口: `8009`

(个人小土豆,求放过)

---

## 目前使用的第三方组件

* [弹弹Play远程访问首页](https://github.com/kaedei/dandanplay-libraryindex)
* [DPlayer](https://github.com/MoePlayer/DPlayer)
* [jQuery](https://github.com/jquery/jquery)
* [FFmpeg](https://github.com/FFmpeg/FFmpeg)
* [
hanzi-convert](https://github.com/uutool/hanzi-convert)

## 感谢
>感谢 [@kaedei](https://github.com/kaedei) 大佬的指教， 使我修完了最后的屎山bug