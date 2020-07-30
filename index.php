<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="css/icon.png" type="image/x-icon">
	<title><?php include_once 'function.php';echo ($site_name); ?></title>
	<link rel="stylesheet" href="./css/bootstrap-4.0.0.css">
	<script src="js/jquery-3.2.1.min.js"></script>
	<style>
		body {
			background-image: url(./src/background.png);
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
			background-color: rgba(255,255,255,0.8);
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a class="navbar-brand" href="#"><?php include_once 'function.php';echo ($site_name); ?></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item active">
						<a class="nav-link" href="./">首页</a>
					</li>
					<!-- @IfNot.IsAnonymous
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							@@Model.UserName
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
							<a class="dropdown-item" href="/logout">注销登录</a>
						</div>
					</li>
					@EndIf -->
					<li class="nav-item">
						<a class="nav-link" href="<?php include_once 'function.php';echo ($About_link); ?>" target="_blank">关于</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="https://github.com/kaedei/dandanplay-libraryindex" target="_blank">帮助改进此页面</a>
					</li>
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
				<?php include_once 'function.php';listRoot($video_root_path,TRUE,$_GET['animeName']);?>
				<a href="./" class="list-group-item list-group-item-action active rounded-0 line-limit-length">返回首页</a>
			</div>
		</div>
		<div class=" col-md-9 col-xs-12 float-left transform">
		<?php include_once 'function.php';mkCardForRoot($video_root_path,$_GET['animeName']);?>
			<div class="col-12 float-right text-center pt-5">
				<p><?php include_once 'function.php';echo ($site_name); ?> 由 弹弹play 提供部分支持.<br /><?php include_once 'function.php'; echoServerInformation()?></p>
				<p></p>
			</div>

		</div>


	</div>



	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap-4.0.0.js"></script>


</body>
</html>