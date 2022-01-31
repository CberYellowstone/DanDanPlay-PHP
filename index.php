<?php 
$start_time=microtime(true);
define('IN_SYS', TRUE);
include_once 'function.php';
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



<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="./css/icon.png" type="image/x-icon">
	<title><?php echo ($site_name); ?></title>
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<script src="js/jquery-3.5.1.min.js"></script>
	<!-- <script src="./js/function.js"></script> -->
	<style>
		body {
			background-image: url(./src/background.jpg);
			background-attachment: fixed;
			background-position: center;
			background-repeat: no-repeat;
			background-size: cover;
		}

		.video-text {
			line-height: 1.3;
		}

		.line-limit-length {
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}

		.nobords {
			margin: 0;
			padding: 0;
		}

		.li {
			position: sticky;
			top: 0;
		}

		.transform {
			background-color: rgba(255, 255, 255, 0.8);
		}
	</style>
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand" href="#"><?php echo ($site_name); ?></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a class="nav-link" href="./">首页</a>
					</li>
					<?php if($_COOKIE["Username"]!=''){echo('<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$_COOKIE["Username"].'</a><div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink"><a class="dropdown-item" href="./login.php?logout=true">注销登录</a></div></li>');} ?>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">远程访问</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								<img class="dropdown-item" src='<?php  if($authorization){$key='true';}else{$key='false';};echo('https://wenhairu.com/static/api/qr/?size=300&text={"about":"请使用支持弹弹Play远程访问功能的客户端扫描此二维码","ip":["'.$remote_addres.'"],"port":'.$remote_port.',"machineName":"'.urljsonDecode($site_name).'","currentUser":"'.urljsonDecode($user_name).'","tokenRequired":'.$key.'}');?>' width="300px" hight="300px">
									<?php  if(!$authorization){echo('<a class="dropdown-item" >请使用 弹弹Play概念版 扫描二维码</a>');}else{echo('<a class="dropdown-item" >当前需要密钥,二维码不可用,请手动输入</a><a class="dropdown-item" >IP地址: '.$remote_addres.'</a><a class="dropdown-item" >端口: '.$remote_port.'</a>');}?>

							</div>
						</li>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">关于</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
								<a class="dropdown-item" href="<?php echo ($About_link); ?>" target="_blank">GitHub</a>
								<a class="dropdown-item" href="<?php echo ($About_link.'/issues'); ?>" target="_blank">Bug反馈</a>
								<a class="dropdown-item" href="https://github.com/kaedei/dandanplay-libraryindex" target="_blank">帮助改进此页面</a>
							</div>
						</li>

						<form id="_form" method="post" action="<?php echo (((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://').$_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"]; ?>">
						<input type="hidden" name="clCache" value="true" />
						</form>
						<?php if($GLOBALS['able_cache']){ echo('<li class="nav-item active"><a class="nav-link" onclick="document.getElementById(\'_form\').submit();">清除缓存</a></li>');} ?>
	


				</ul>
				<form class="form-inline my-2 my-lg-0" method="POST" action="./">
					<input class="form-control mr-sm-2" type="text" name="q" placeholder="在这里搜索哦ο(=•ω＜=)ρ⌒☆..." data-form-field="seach" required="" aria-label="Search">
					<button class="btn btn-outline-success my-2 my-sm-0" type="submit">搜索</button>
				</form>
			</div>
		</div>
	</nav>

	<div class="clearfix row nobords">
		<div class="col-md-3 col-xs-12 float-left nobords">
			<div class="list-group  li">
				<a href="" class="list-group-item list-group-item-action active rounded-0 line-limit-length">作品列表</a>
				<?php listRoot($video_root_path,TRUE,$_GET['animeName']);?>
				<a href="./" class="list-group-item list-group-item-action active rounded-0 line-limit-length">返回首页</a>
			</div>
		</div>
		<div class=" col-md-9 col-xs-12 float-left transform">
		<?php mkCardForRoot($video_root_path,$_GET['animeName'],$_POST['q']);?>
			<div class="col-12 float-right text-center pt-5">
				<p><?php echo ($site_name." | Base on 弹弹PlayAPI.</br>");echoServerInformation(microtime(true)-$start_time)?></p>
				<p></p>
			</div>
		</div>
	</div>

	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>

</body>
</html>

<?php
mkCache(1);
?>