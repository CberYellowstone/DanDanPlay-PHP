# 请移至 [DanDanPlay-Python](https://github.com/CberYellowstone/DanDanPlay-Python) (正在重构中)

# 此项目已不建议使用，因 DanDanPlay-Android 现行版本默认自主获取弹幕，建议换用 WebDav，后续重构将只包含API部分
## 本项目即将使用 Python 重构

![DanDanPlay-PHP](https://socialify.git.ci/CberYellowstone/DanDanPlay-PHP/image?description=1&descriptionEditable=DanDanPlay%20%E8%BF%9C%E7%A8%8B%E8%AE%BF%E9%97%AE%20%E7%9A%84%20PHP%20%E5%AE%9E%E7%8E%B0%E7%89%88%E6%9C%AC&font=Raleway&forks=1&issues=1&logo=https%3A%2F%2Fcdn.jsdelivr.net%2Fgh%2FCberYellowstone%2FDanDanPlay-PHP%40master%2Fsrc%2Fddp-black.png&pattern=Brick%20Wall&pulls=1&stargazers=1&theme=Dark)

# DanDanPlay-PHP

基于 PHP 实现的 [弹弹Play远程访问首页](https://github.com/kaedei/dandanplay-libraryindex)（包含 媒体库内容的展示、视频播放、弹幕 以及 远程访问API）

~~新人作品，可能不是很完美，但是在不断改进~~

用于 **类 Linux 服务器** 的部署，已在 `Centos 7.9` 上通过测试

（主要用于 `NAS` 等设备上部署，若是 `WindowsServer` 则建议直接使用 `弹弹Play 桌面版` ）

PHP版本要求： `PHP 7+` ，支持 `PHP 8.x`，个人部署环境是 `PHP7.4` ，理论上更低版本也行，请自行尝试

另外需要安装 `FFmpeg` ，默认调用路径为 `/usr/bin/ffmpeg` ，可自行更改，版本要求 `4.3.x` 以上

（自行尝试更低版本）

本项目用到了 [DanDanPlay API](https://api.acplay.net/swagger/ui/index#/) ，弹幕会比电脑端少一些，但基本一致（和手机端理论一致）

---

## 说明

* 访问 `do.php` 可以实现刷新主页，添加新番之后需要访问一次 `do.php` （可以添加到 Crontab ）
* 里面还有部分的测试用语句，不影响性能和使用，介意的话自己删掉就行
* 使用开发版（直接clone仓库）可能将获得更好体验，因其包含更多及时的 Bug 修复

---
### 目录结构：

>配置文件 `config.php` 以及 `/api/v1/config.php` ，使用前请自行更改相关参数
>
>**包含**番剧视频文件的 **文件夹** 需放在 `/video` 目录下，可用 **番剧目录** 的软链接代替，
>> e.g.
>>
>>```
>>video
>>|
>>└───来自风平浪静的明天
>>│   │   [ktxp][Nagi_No_Asukara][01][720p][GB].mp4
>>│   │   [ktxp][Nagi_No_Asukara][02][720p][GB].mp4
>>│   │   ...
>>│    
>>└───末日时在做什么？有没有空？可以来拯救吗？
>>    │   [KxIX]Shuumatsu Na...Moratte Ii Desuka 01[GB][1080P].mp4
>>    │   [KxIX]Shuumatsu Na...Moratte Ii Desuka 02[GB][1080P].mp4
>>    │   ...
>>```
>>
>但是 链接/目录名 要和标准中文译名（完全）一致（可从 弹弹Play 复制）
>>下一步将计划改进为随意命名
>
>番剧目录下不可有**非视频文件**，且番剧视频文件名要能被识别 （罗马字标题或者中文译名）
>
>如果识别错误可以手动去 `/data` 目录下更改相应 json ，具体方法自行摸索（懒得写了）
>
>>（改了识别逻辑之后理论上只要文件夹译名正确就不会识别错了）
>>
>>（之后还要改进手动纠错）
>

---
 
### 远程访问 API 功能：

>需开启 Apache 的 ReWrite 功能，方法请自行百度，已经内置 `.htaccess` 文件。
>
>Nginx 用户请自己摸索适配（我相信你们都是大佬），主要是我也不用 Nginx
>
>另外本项目为了提高 PHP 视频流性能，使用了 `mod_xsendfile` 模块，此为 `Apache Httpd` 模块，需自行安装并启用。
>
>此处提供 `Apache` 的配置文件：
>>
>>XSendFile on
>>
>>XSendFilePath /path/ddp/video/
>>
>
>P.S. `Nginx`也有同种模块，具体方法请百度
>
> 弹弹Play概念版APP 并 **不** 支持 HTTPS 协议，所以 远程访问 需使用单独的 HTTP 端口
> 
>**同时，弹弹Play概念版APP 的播放器内核 或 需更改为 `EXO Player` ，否则部分番剧会报错**
>>**应该与视频源文件以及PHP有关**,有问题就换 `EXO Player` 吧
>
>此外，远程访问 所使用的域名需占用整个域名，不得安装在二级目录下
>>e.p. 主站用 `https://xxx.xxx.xxx/ddp` 是可以的
>>
>>但是， 远程访问的 API 地址格式 **必须** 为 `http://xxx.xxx.xxx/`
>>
>>由于弹弹Play概念版自身的问题，番剧的排序会有误，但是本程序已主动适配以规避该错误 
>>
>>若用户开启了缓存功能，那么打开远程访问界面右上角的遥控器将会触发清除缓存功能

---
### 搜索功能:
>
>目前 繁体 以及 和制汉字 与简体中文之间的互相转换有一些问题，暂时不解决
>
>>P.S.（DanDanPlay 自己都没解决这个问题）
>
>以及左边的番剧列表并不会更新，完全不影响使用（大概），所以就不改了

---
### 账户认证功能

>在 `config.php` 里面更改相关选项即可打开登录验证，支持添加多用户

---
### 缓存功能

>在 `config.php` 里面更改相关选项即可打开缓存选项，请确保 `cache` 目录有读写权限


---

## 已知问题 / TODOS

>>* ~~识别准确率有待提升，已经想到了解决方法，近期修复~~
>>* （已解决：2020年10月25日，依赖文件夹名称识别番剧）
>
>* 番剧识别方法仍有改进空间，之后会改进
>>* ~~有的番剧弹幕会报错，没空修，有愿意接坑的可以试试接屎山~~
>>* （已解决: 2020年10月3日）
>
>* 发布的弹幕并不会同步到服务器，不打算安排了，不影响使用
>>* `do.php` 页面样式大整改（coming soon~）
>>* 将包含手动纠错番剧识别结果等

---

## 多平台

* [GitHub](https://github.com/CberYellowstone/DanDanPlay-PHP)
* [Gitee](https://gitee.com/Yellowstone/DanDanPlay-PHP)
* [腾讯工蜂](https://git.code.tencent.com/Yellowstone/DanDanPlay-PHP)

---

## DEMO
个人家宽小土豆，求放过

|  类型         | 访问方式  |
|    :-:      |   :-:    |
| 网页版 Damo  | [访问链接](https://apps.ystone.top:488/ddp/) |
| 远程访问API  | 地址: `apps.ystone.top` , 端口: `8009` |

---

## 目前使用的第三方组件

* [弹弹Play远程访问首页](https://github.com/kaedei/dandanplay-libraryindex)
* [DPlayer](https://github.com/MoePlayer/DPlayer)
* [jQuery](https://github.com/jquery/jquery)
* [FFmpeg](https://github.com/FFmpeg/FFmpeg)
* [hanzi-convert](https://github.com/uutool/hanzi-convert)

## 感谢
> [@kaedei](https://github.com/kaedei)
