<?php define('IN_SYS', TRUE); include_once 'function.php';
if ($_GET['logout'] == 'true' or !$authorization) {
	setcookie("Username",'',time()-1);
	setcookie("Auth",'', time()-1);
}
if(!$authorization) {
	sendStatusCode(303, 'See Other', './', 0);
	exit();
}
if(checkUserAndPassword($_POST['Username'],$_POST['Password'])){
	if($_POST['RememberMe']=="on"){
		setcookie("Username",$_POST['Username'],time()+60*60*24*30);
		setcookie("Auth",hash('sha256',$_POST['Password']), time()+60*60*24*30);	
	}else{
		setcookie("Username",$_POST['Username'],time()+3600);
		setcookie("Auth",hash('sha256',$_POST['Password']), time()+3600);	
	}
	sendStatusCode(303, 'See Other', './', 0);
	exit();
}elseif($_POST['Username']!=""){
	sendStatusCode(303, 'See Other', './login.php?error=true', 0);
	exit();
}
if(checkUserAndPasswordFromCookie($_COOKIE["Username"], $_COOKIE["Auth"]) and !$_GET['logout'] == 'true') {
	sendStatusCode(303, 'See Other', './', 0);
} 


?>

<!DOCTYPE html>
<html lang="zh-CN">

<head>
	<title><?php echo ($site_name); ?></title>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="shortcut icon" href="./css/icon.png" type="image/x-icon" />
	<script src="./js/bootstrap.min.js"></script>
	<script src="./js/jquery-3.5.1.min.js"></script>
	<script src="./js/fontawesome.min.js"></script>
	<link rel="stylesheet" href="./css/bootstrap.min.css" />
	<style type="text/css">
		html,
		body {
			background-image: url(./src/background.jpg);
			background-size: cover;
			background-repeat: no-repeat;
			height: 100%;
		}

		.container {
			height: 100%;
			align-content: center;
		}

		.card {
			height: 350px;
			margin-top: auto;
			margin-bottom: auto;
			width: 400px;
			background-color: rgba(0, 0, 0, 0.5) !important;
		}

		.card-header h4 {
			margin-top: 20px;
			color: white;
		}

		.input-group-prepend span {
			width: 40px;
			/* ReSharper disable once InvalidValue */
			background-color: #1BA1E2;
			color: white;
			border: 0 !important;
		}

		input:focus {
			outline: 0 !important;
			box-shadow: 0 0 0 0 !important;
		}

		.remember {
			color: white;
		}

		.remember input {
			width: 20px;
			height: 20px;
			margin-left: 15px;
			margin-right: 5px;
		}

		.login_btn {
			color: white;
			/* ReSharper disable once InvalidValue */
			background-color: #1BA1E2;
			width: 100px;
		}

		.login_btn:hover {
			color: black;
			background-color: white;
		}

		.links {
			color: white;
		}

		.links a {
			margin-left: 4px;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="d-flex justify-content-center h-100">
			<div class="card">
				<div class="card-header text-center">
					<h4>登录<?php echo ($site_name); ?></h4>
				</div>
				<div class="card-body">
					<form method="post">
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="Username" class="form-control" placeholder="用户名" required />
						</div>
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="Password" class="form-control" placeholder="密码" required />
						</div>
						<div class="row align-items-center remember">
							<input type="checkbox" name="RememberMe" id="RememberMe" . />
							<label for="RememberMe">
								记住我
							</label>
						</div>
						<div class="form-group">
							<input type="submit" value="登录" class="btn float-right login_btn" />
						</div>
					</form>
				</div>
				<?php if ($_GET['error'] == 'true') {echo ('<div class="card-footer"><p class="d-flex justify-content-center text-danger">用户名或密码错误</p></div>');} ?>
				<!--<div class="d-flex justify-content-center links">
						没有账号？<a href="#">注册</a>
					</div>
					<div class="d-flex justify-content-center">
						<a href="#">忘记密码？</a>
					</div>-->
			</div>

		</div>
	</div>
	</div>
</body>

</html>