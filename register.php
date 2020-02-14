<?php
//
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//
$dbconnecterror = FALSE;
$dbh = NULL;

//
$email = "";
$username = "";
$password = "";
$securityquestion = "";
$securityanswer = "";


//
$errors = array();

//
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	//
	$email = trim($_POST['email']);
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$securityquestion = trim($_POST['securityquestion']);
	$securityanswer = trim($_POST['securityanswer']);

	//
	try{

		//
		$conn_string = "mysql:host=localhost;dbname=wishit";
		$dbh= new PDO($conn_string, "root", "password");
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//
		$sql = "INSERT INTO users (email, username, password, securityquestion, securityanswer) " .
			"VALUES ('$email', '$username', '$password', '$securityquestion', '$securityanswer')";			
		$stmt = $dbh->prepare($sql);
		$success = $stmt->execute();

		// 
		if ($success) {

			//
			header("Location: index.php?registered=true");
			exit();
			
		//
		} else {
			$errors[] = "There was a problem completing your registration. Error code " . $dbh->errorCode();
		}

	//
	}catch(Exception $e){
		$errors[] = "There was an error connecting to the database. Please try again later. Error code " . $dbh->errorCode();
	}

}

?>


<!doctype html>
<html lang="en">
	
	<?php require_once('include/head.php');?>

	<body>
		<?php require_once('include/header.php');?>
		
		<h3>Register a new account</h3>
		<?php 
		//
		if (count($errors) > 0) { ?>
			<div class="error">
				The following errors occurred:
				<ul>
				<?php //    ?>
				<?php for($i = 0; $i < count($errors); $i++) { ?>
					<li><?php echo $errors[$i]; ?></li>
				<?php } ?>
				<ul>
			</div>
		<?php } ?>

		<div>

			<!-- 			 -->
			<form name="register" action="register.php" method="post" onsubmit="return validateForm()">
				<input type="text" name="username" id="username" placeholder="Pick a username" value="<?php echo $username; ?>">
				<br>
				<input type="password" name="password" id="password" placeholder="Provide a password" value="<?php echo $password; ?>" >
				<br>
				<input type="email" name="email" id="email" placeholder="Please enter your email address" size="50" value="<?php echo $email; ?>">
				<br>
				<input type="text" name="securityquestion" id="securityquestion" placeholder="Please enter your security question" size="50" value="<?php echo $securityquestion; ?>" >
				<br>
				<input type="text" name="securityanswer" id="securityanswer" placeholder="Please enter your security answer" size="50" value="<?php echo $securityanswer; ?>" >
				<br>								
				<input type="submit" value="Register" name="register" id="submitBtn">
			</form>
		</div>
		
		<!-- 		 -->
		<a class="loginLinks" href="index.php">Sign in?</a>

		<!-- 			 -->
		<script>

			//
			function validateForm() {

				//
				var username = document.forms["register"]["username"].value;
				if (username == "") {
					alert("Username must be filled out");
					return false;
				}

				//
				var password = document.forms["register"]["password"].value;
				if (password == "") {
					alert("Password must be filled out");
					return false;
				}
				
				//
				var email = document.forms["register"]["email"].value;
				if (email == "") {
					alert("Email must be filled out");
					return false;
				}

				//
				var securityquestion = document.forms["register"]["securityquestion"].value;
				if (securityquestion == "") {
					alert("Security question must be filled out");
					return false;
				}			  

				//
				var securityanswer = document.forms["register"]["securityanswer"].value;
				if (securityanswer == "") {
					alert("Security answer must be filled out");
					return false;
				}				  
			  
			}
			
			
		</script>
		
	</body>
</html>
