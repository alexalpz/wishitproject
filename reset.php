<?php
	
	//
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	//
	$dbconnecterror = FALSE;
	$dbh = NULL;
	$dbReadError = FALSE;

	//
	$errors = array();

	//
	$username = "";
	$password = "";
	
	//
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		// 
		$username = $_POST['username'];

		// 	
		if(array_key_exists("answer", $_POST)){
			$answer = $_POST['answer'];
		} else{
			$answer = NULL;
		}

		// 	
		try{

			//
			$conn_string = "mysql:host=localhost;dbname=wishit";
			$dbh= new PDO($conn_string, "root", "password");
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//
			$sql = "SELECT * FROM users where username = '$username';";
			$stmt = $dbh->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			//
			if (count($result) > 0) {

				//
				$question = $result[0]['securityquestion'];
				$correctanswer = $result[0]['securityanswer'];
				$password = $result[0]['password'];

				//
				if (!empty($answer)) {

					//
					if ($answer != $correctanswer) {

						//
						$errors[] = "That is an incorrect answer!";

					}

				}

			//
			} else
				
				//
				$errors[] = "Uh oh! Could not find your username";

		//	
		} catch (PDOException $e) {
			$errors[] = "Uh oh! There was an error connecting to the database. Please try again later. Error code " . $dbh->errorCode();
		}
		
	}
?>
<!doctype html>

<html lang="en">

	<!-- 		 -->
	<?php require_once('include/head.php');?>

	<body>
		
		<!-- 		 -->		
		<?php require_once('include/header.php');?>
		
		<h3>Forgot Password</h3>

		<!-- 		 -->
		<form method="post" action="reset.php">
			<input type="text" name="username" id="username" placeholder="Enter your username" required="required" size="40" value="<?php echo $username;?>" <?php if(isset($question)) { echo "readonly"; } ?>
			<br>
			<?php 
			//
			if (isset($question)) { 
				?>
				<br>
				<br>
				Your security question:
				<b><?php echo $question; ?></b>
				<br>
				<br>
				<label for="answer">Security answer:</label>
				<input type="text" name="answer" id="answer" size="40" value="<?php echo $answer;?>">	
				
				<?php 
				//
				if ($answer == $correctanswer) { 
				?>
					<br>
					<br>
					<label for="password">Your Password:</label>
					<input type="text" name="password" id="password" size="40" value="<?php echo $password;?>">
					
				<?php } ?>
			
			<?php } ?>
			
			
			<br>
			<br>			
			<input type="submit" value="Submit" id="submitBtn">

		</form>
		<a class="loginLinks" href="register.php">Need to create an account?</a>
		
		<?php 
		//
		if (count($errors) > 0) { ?>
			<div class="error">
				<ul>
				<?php 
				//
				for($i = 0; $i < count($errors); $i++) { ?>
					<?php echo $errors[$i]; ?>
				<?php } ?>
				<ul>
			</div>
		<?php } ?>

	</body>
</html>
