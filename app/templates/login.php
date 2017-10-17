
<?php
	if(isset($_GET['un'])){
		$user = $_GET['un'];
        $_SESSION['cars-user'] = $user;
		echo "<script type='text/javascript'>window.location.assign('/')</script>";
	}
	require_once("_Layouts.php");
	getHeader("Login");
?>

<form action="/login" method="get">
  USERNAME: <input type="text" name="un"><br>
  <input type="submit" value="Submit">
</form>

<?php
	getFooter();
?>
