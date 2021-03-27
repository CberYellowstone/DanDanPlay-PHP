<?php include_once 'function.php';
@header("Cache-Control: no-cache, must-revalidate");
if($authorization and !checkUserAndPasswordFromCookie($_COOKIE["Username"], $_COOKIE["Auth"])) {
	sendStatusCode(303, 'See Other', './login.php', 0);
} 
if(!$authorization){
	setcookie("Username",'',time()-1);
	setcookie("Auth",'', time()-1);
}
clCache($_SERVER['REQUEST_URI']);
mkCache(0);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title><?php include_once 'function.php';echo (removeQuote(readVideoInformationFromMD5($_GET['video'])[0]['animeTitle'])." - ".removeQuote(readVideoInformationFromMD5($_GET['video'])[0]['episodeTitle'])." - ".$site_name); ?></title>
	<link rel="shortcut icon" href="./css/icon.png" type="image/x-icon">
	<link href="./css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="./css/DPlayer.min.css">
	<script src="./js/DPlayer.min.js"></script>
	<!-- <script src="./js/function.js"></script> -->
	<style type="text/css">
		/* ReSharper disable InvalidValue */
		/* ReSharper disable CssNotResolved */
		.dplayer {
			width: 100%;
			top: 3%;
			height: 70%;
			margin-bottom: 40px;
		}

		.dplayer-danmaku {
			font-size: 25px;
			height: <?php include_once 'function.php';echo ($DanmakuArea); ?> ;
		}

			.dplayer-danmaku .dplayer-danmaku-right.dplayer-danmaku-move {
				-webkit-animation: <?php include_once 'function.php';echo ($DanmakuDurationCss); ?> ;
				animation: <?php include_once 'function.php';echo ($DanmakuDurationCss); ?> ;
				-webkit-animation-play-state: paused;
				animation-play-state: paused;
			}

			.dplayer-danmaku .dplayer-danmaku-item {
				-webkit-text-stroke: 0.1px black;
				/* text-stroke: 0.1px black; 兼容性问题*/
				text-shadow: 1.0px 1.0px 0.5px rgba(0, 0, 0, .5);
			}

		/* mobile */

		/* 控制条 mask 高度调小，避免影响双击切换暂停播放 */

		.dplayer-mobile .dplayer-controller-mask {
			height: 60px;
		}

		/* 弹幕字体调小 */

		.dplayer-mobile .dplayer-danmaku {
			font-size: 20px;
		}

		.dplayer.dplayer-arrow.dplayer-mobile .dplayer-danmaku {
			font-size: 20px;
		}

		/* ReSharper restore InvalidValue */
		/* ReSharper restore CssNotResolved */
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand" href="."><?php include_once 'function.php';echo ($site_name); ?></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a class="nav-link" href="./">首页</a>
					</li>
					<?php include_once 'function.php';if($_COOKIE["Username"]!=''){echo('<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$_COOKIE["Username"].'</a><div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"><a class="dropdown-item" href="./login.php?logout=true">注销登录</a></div></li>');} ?>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">远程访问</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<img class="dropdown-item" src='<?php include_once 'function.php'; if($api_needkey){$key='true';}else{$key='false';};echo('https://wenhairu.com/static/api/qr/?size=300&text={"about":"请使用支持弹弹Play远程访问功能的客户端扫描此二维码","ip":["'.$remote_addres.'"],"port":'.$remote_port.',"machineName":"'.urljsonDecode($site_name).'","currentUser":"'.urljsonDecode($user_name).'","tokenRequired":'.$key.'}');?>' width="300px" hight="300px">
								<?php include_once 'function.php'; if(!$api_needkey){echo('<a class="dropdown-item" >请使用 弹弹Play概念版 扫描二维码</a>');}else{echo('<a class="dropdown-item" >当前需要密钥,二维码不可用,请手动输入</a><a class="dropdown-item" >IP地址: '.$remote_addres.'</a><a class="dropdown-item" >端口: '.$remote_port.'</a>');}?>
							</div>
						</li>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">关于</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								<a class="dropdown-item" href="<?php include_once 'function.php';echo ($About_link); ?>" target="_blank">GitHub</a>
								<a class="dropdown-item" href="<?php include_once 'function.php';echo ($About_link.'/issues'); ?>" target="_blank">Bug反馈</a>
								<a class="dropdown-item" href="https://github.com/kaedei/dandanplay-libraryindex" target="_blank">帮助改进此页面</a>
							</div>
						</li>

						<form id="_form" method="post" action="<?php echo (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://').$_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]; ?>">
						<input type="hidden" name="clCache" value="true" />
						</form>
						<?php if($GLOBALS['able_cache']){ echo('<li class="nav-item active"><a class="nav-link" onclick="document.getElementById(\'_form\').submit();">清除缓存</a></li>');} ?>




				</ul>
			</div>
		</div>
	</nav>

	<div class="container">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="./">首页</a>
				</li>
				<li class="breadcrumb-item">
					<a href="./index.php?animeName=<?php include_once 'function.php';echo (removeQuote(readVideoInformationFromMD5($_GET['video'])[0]['animeTitle'])); ?>"><?php include_once 'function.php';echo (removeQuote(readVideoInformationFromMD5($_GET['video'])[0]['animeTitle'])); ?></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
				<?php include_once 'function.php';echo (removeQuote(readVideoInformationFromMD5($_GET['video'])[0]['episodeTitle'])); ?>
				</li>
			</ol>
		</nav>

		<div id="dplayer"></div>
		<div class="card">
			<div class="card-header">剧集列表</div>
			<div class="card-body">
				<div class="list-group list-group-flush">
				<?php include_once 'function.php';mkListFromMD5($_GET['video']); ?>
				</div>
			</div>
		</div>
	</div>

	<hr />
	<div class="container">
		<div class="row text-center">
			<div class="col-12">
				<p><?php include_once 'function.php';echo ($site_name); ?>  | Base on 弹弹PlayAPI</p>
			</div>
		</div>
	</div>

	<script src="./js/jquery-3.5.1.min.js"></script>
	<!--<script src="./js/popper.min.js"></script>-->
	<script src="./js/bootstrap.min.js"></script>
	<script>
		$(document).ready(function () {
			var dp = new DPlayer({
				container: document.getElementById('dplayer'), //播放器容器元素
				theme: '#0099FF', //控件的颜色
				loop: false, //循环
				screenshot: true, //截图
				hotkey: true, //热键
				preload: 'metadata', //预加载
				//logo: 'http://www.dandanplay.com/logo.png',        //播放器左上角logo
				playbackSpeed:[0.5, 0.75, 1, 1.25, 1.5, 2],
				volume: 1, //默认音量
				mutex: true, //互斥，阻止多个播放器同时播放
				video: {
					url: '<?php include_once 'function.php';echo (getVideoFileFromMD5($_GET['video'])); ?>',
					pic: '<?php include_once 'function.php';echo (getVideoPicFromMD5($_GET['video'])); ?>',
					type: 'auto'
				},
				danmaku: {
					id: '<?php include_once 'function.php';echo ($_GET['video']); ?>', //弹幕库id
					api: './function.php?action=getCommentFromMD5&md5=', //弹幕库api
					bottom: '15%', //底部距离
					unlimited: false, //无限制
					maximum: 30 //最大弹幕
				},
				subtitle: {
					url: './function.php',
					//TODO: 字幕
					type: 'webvtt',
					fontSize: '25px',
					bottom: '10%',
					color: '#ffffff'
				},
			});
			$(".dplayer-video").attr("crossorigin", "use-credentials");
		});
	</script>
	<?php include_once 'function.php';saveLastTime($_GET['video']); ?>
</body>
</html>
<?php include_once 'function.php';mkCache(1); ?>