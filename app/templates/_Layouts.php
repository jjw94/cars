<?php
	function getNavTabs(){
		$user = $_SESSION['cars-user'];
		$userType = $_SESSION['cars-role'];

		$tabs = array();
		if($userType == "Department Administrator"){
			$tabs["main"] = array("Reports", "Course Selection", "Course Assignment", "Management");
			$tabs["mgmt"] = array("Course", "Department");
		}
		else if($userType == "Department Manager"){
			$tabs["main"] = array("Reports", "Management");
			$tabs["mgmt"] = array("Course", "Department");
		}
		else if($userType == "System Administrator"){
			$tabs["main"] = array("Reports", "Management", "System Docs");
			$tabs["mgmt"] = array("Application", "Users");
		}
		else if($userType == "Instructor" || $userType == "Student Employee"){
			$tabs["main"] = array("Reports", "Course Selection");
		}
	return $tabs;
	}

	function generateLink($key){
		if($key =="Reports"){
			return "/reports";
		}
		else if($key =="Course Selection"){
			return "/course/selection";
		}
		else if($key =="Department"){
			return "/department/users";
		}
		else if($key =="Course Assignment"){
			return "/course/assignment";
		}
		else if($key == "Course"){
			return "/course/management";
		}
		else if($key == "Application"){
			return "/admin/application";
		}
		else if($key == "System Docs"){
			return "/admin/docs/";
		}
		else if($key == "Users"){
			return "/admin/users";
		}
		else if($key =="Login"){
			return "/login";
		}
		else if($key =="Logout"){
			return "/logout";
		}
	}

	function getHeader($current = "Home"){
		//Only give the user tabs if they are authenticated
		if(isset($_SESSION['auth'])) {
			$tabs = getNavTabs();
		}

	?>
		<!DOCTYPE html>
		<html>
		<head>
			<meta http-equiv="content-type" content="text/html; charset=utf-8" />
			<title>CARS | <?php echo $current ?></title>
			<link rel="stylesheet" href="/Content/Bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" href="/Content/Bootstrap/css/bootstrap-theme.min.css">
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
			<link rel="stylesheet" href="/Content/CSS/main.css">
			<link rel="stylesheet" href="/Content/CSS/style.css">
			<link rel='stylesheet' href='/Content/CSS/github-markdown.css'>
			<script src="/Content/JS/jquery-3.1.1.min.js"></script>
			<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
			<script src="/Content/JS/handlebars-v4.0.5.js"></script>
			<script src="/Content/JS/jquery.dynatable.js"></script>
			<script src="/Content/JS/cars.js"></script>
		</head>
		<body>
			<nav class="nav" style="background:#000;color:#fff;height:75px;">
				<div style="width:1060px;margin:0 auto;">
					<div class="float-left" onclick="window.location = '/'">
						<h2>CARS</h2>
					</div>
					<div class="nav-buttons">
						<ul>

							<?php
								//Only show tabs for authenticated and authorized users
								if(isset($_SESSION['auth'])) {
									foreach ($tabs["main"] as $tab) {
										$css = "";
										if($tab == $current){ $css = "current";}
										if($tab == "Management"){
											echo "<li class='dropdown'><a href='".generateLink($tab)."' class='".$css."'>".$tab."</a>";
											echo "<div class='dropdown-content'><ul>";
												    foreach ($tabs["mgmt"] as $value) {
												    	echo "<li><a href='".generateLink($value)."' class='".$css."'>".$value."</a></li>";
												    }
												  echo "</ul></div></li>";
										}
										else{
											echo "<li><a href='".generateLink($tab)."' class='".$css."'>".$tab."</a></li>";
										}

									}
								}

								//If a user is set, show them a log out button
								if(isset($_SESSION['cars-user'])){
									$user = $_SESSION['cars-user'];

									echo '<li><a href="/logout">Logout of '.$user.'</a></li>';

								}
							?>
						</ul>
						<div class="help-link">
							<a href="/help/">
								<i class="fa fa-2x fa-question"></i>
							</a>
						</div>
					</div>
				</div>
			</nav>
			<div class="container">

<?php } ?>

<?php function getFooter(){ ?>
	<div class="push"></div>
			</div>

			<div class="footer">
				<h6 style="text-align:center;padding-top:5px;">&copy; Team White | 2016</h6>
			</div>
		</body>

		</html>
<?php } ?>
