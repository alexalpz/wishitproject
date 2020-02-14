<?php
/*
Note: Be sure to set file ownership/permissions after creating or modifying files.
sudo chown -R www-data: /var/www/html
sudo chmod -R g+w /var/www/html/uploads
sudo chmod -R g+w /var/www/html/thumb
*/

//
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//
$dbconnecterror = FALSE;
$dbh = NULL;

//
$username = "";
$password = "";

//
$errors = array();

//
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	
	//
	if(array_key_exists("logout", $_GET)){
		setcookie("wishit_session_id", "", time() - 3600);
		setcookie("wishit_isadmin", "", time() - 3600);
	}
}

//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// 
	$username = $_POST['username'];
	$password = $_POST['password'];

	try{
		//
		$conn_string = "mysql:host=localhost;dbname=wishit";
		$dbh= new PDO($conn_string, "root", "password");
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//
		$sql = "SELECT * FROM users where username = '$username' AND password = '$password';";
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		$records = $stmt->fetchAll();

		// 
		if (count($records) > 0) {

			//
			$email = $records[0]['email'];
			$isadmin = $records[0]['isadmin'];

			//
			$expires = time()+60*60*24*30;
			$token = base64_encode($email);
			setcookie('wishit_session_id', $token, $expires);
			if($isadmin==1){
				setcookie('wishit_isadmin', "1", $expires);
			}

			// 
			header("Location: wishlist.php");
			exit();

		//
		}else {
			$errors[] = "Uh oh! Bad username and/or password used.";
		}


	//
	}catch(Exception $e){
		$errors[] = "Uh oh! here was an error connecting to the database. Please try again later. Error code " . $dbh->errorCode();
	}

}

//
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	//
	if (isset($_GET['notloggedin'])) {

		//
		$errors[] = "Login required.";
		
	}

}

?>


<!doctype html>
<html lang="en">

	<?php require_once('include/head.php');?>
	
	<body>
		<?php require('include/header.php');?>
		
		<h3>Sign in</h3>


		<?php 
		//
		if (isset($_GET['registered'])) { ?>
			<div class="message">
				Registration successful. Please login.
			</div>
		<?php } ?>

		<div>
			<!-- 			 -->
			<form method="post" action="index.php">
				
				<input type="text" name="username" id="username" placeholder="Username" value="<?php echo $username; ?>">
				<br>
	
				<input type="password" name="password" id="password" placeholder="Password" value="<?php echo $password; ?>">
				<br>
	
				<input type="submit" value="Login" name="login" id="submitBtn">
				<br>
			</form>
		</div>


		<!-- 		 -->
		<a class="loginLinks" href="register.php">Create Account?</a> &nbsp; &nbsp;
		<a class="loginLinks" href="reset.php">Forgot Password?</a>
		
		
		<!-- 		 -->
		<?php if (count($errors) > 0) { ?>
			<div class="error">
				<ul>
				<?php //    ?>
				<?php for($i = 0; $i < count($errors); $i++) { ?>
					<?php echo $errors[$i]; ?>
				<?php } ?>
				<ul>
			</div>
		<?php } ?>
		
		
	</body>
</html>