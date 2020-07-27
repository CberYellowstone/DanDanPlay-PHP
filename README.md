# dandanplay-libraryindex

基于PHP重构的 弹弹play Windows/UWP客户端远程访问功能的html首页（媒体库内容的展示以及视频播放）

用于服务器部署

用到了DanDanPlay API

弹弹play每次更新时会附带此项目中master分支最新版的html/js/css和图片文件。

## 文件说明

* `index.php` 为首页，将会通过 `http://本机ip/` 访问
* `video.html` 为视频播放页面，将会通过 `http://本机ip/web/{视频id}` 访问
* `login.html` 为登录页，当启用了web验证功能时，匿名用户访问任意页面将会被重定向到此页面，将会通过 `http://本机ip/login.html` 访问
* 所有javascript文件需要放在 `js` 文件夹中
* 所有css文件和图片文件需要放在 `css` 文件夹中


## 目前使用的第三方组件

* [DPlayer](https://github.com/MoePlayer/DPlayer)
* jQuery
* Bootstrap