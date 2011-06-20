<!DOCTYPE HTML>

<html lang="en">

<head>
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="css/reset.css"/>
	<link rel="stylesheet" type="text/css" href="css/default.css"/>
	<script src="js/jquery-1.5.1.min.js"></script>
	<script src="js/main.js"></script>
</head>

<body>
<div id="wrap">
	<div id="header">
		<a href="index.php">
			<img src="images/logo.png" id="logo"/>
			<h1>Mediashake</h1>
		</a>
		<ul id="navigation">
			<li><a href="?page=Home">Home</a></li>
			<li><a href="?page=News">News</a></li>
			<li><a href="?page=Members">Members</a></li>
			<li class="login"><a href="#">Login</a></li>
		</ul>
	</div>
	<div id="login">
		<img src="images/loginbox-top.png" class="top">
		<div class="title">Login</div>
		<form>
			<input type="text" name="username" />
			<input type="password" name="password" />
			<input type="submit" value="Login" />
		</form>
	</div>
	