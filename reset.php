<?php
	
	//The first line tells PHP you'd like to see any errors that occur [1]. The second line will determine if the errors will be displayed or hidden to the user. The third line "error_reporting" sets which PHP errors are reported[2]. "E_ALL" reports all PHP errors[3].
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	
	///Setting "dbconnecterror" variable to “false” won't display anything.  "dbh" is placeholder variable that will connect to the webserver.
	$dbconnecterror = FALSE;
	$dbh = NULL;
	$dbReadError = FALSE;

	//declare an empty error array
	$errors = array();

	//This sets the variables to blank
	$username = "";
	$password = "";
	
	//The purpose of this function is to check whether the request was done via GET or POST method, in this case it is POST.
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		//   $_POST is widely used to pass variables [4]. Gets an associated array from query result resource. Username by default is set to blank.
		$username = $_POST['username'];

		// Delcaring variables. The array_key_exists() checks whether a specific key ("answer") or index is present inside an array ($_POST) or not [5]. The special NULL value represents a variable with no value [6].
		if(array_key_exists("answer", $_POST)){
			$answer = $_POST['answer'];
		} else{
			$answer = NULL;
		}

		// All of the code within the try block is executed until an exception is potentially thrown. 
		try{

			//First line is connecting to web server and database and the second line is connecting as the admin using the password required to be authorized.  PDO ERRMODE attribute (controls error reporting) to raise a PDOException that tells you what went wrong when it goes wrong [7]. 
			$conn_string = "mysql:host=localhost;dbname=wishit";
			$dbh= new PDO($conn_string, "phpmyadmin", "studentstudent");
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//Perfoming a SQL querie, listing all information about certain user. The "prepare" statement is sent to the database server[8]. During execute the client binds parameter values and sends them to the server [8]. Fetch all of the remaining rows in the result set from database [9] 
	//TODO: Give execution it's own variable. Call proper variable name  in "$result" variable once corrected name. 
			$sql = "SELECT * FROM users where username = '$username';";
			$stmt = $dbh->prepare($sql);
			$stmt->execute();
			$result = $stmt->fetchAll();
				
			//If query execution above has been succesful and executed, continue with the lines of code below. 
			if (count($result) > 0) {

				//Recursive and multi-dimensional arrays stored in variables [10].
				$question = $result[0]['securityquestion'];
				$correctanswer = $result[0]['securityanswer'];
				$password = $result[0]['password'];

				//!empty() will accept any arguments. No warning if variable does not exists. 
				if (!empty($answer)) {

					// If the security answer is not blank, the continue further into the code. 
					if ($answer != $correctanswer) {

						//Storing an error message in the variable. Has no ouput to users. 
						$errors[] = "That is an incorrect answer!";

					}

				}

			//If the querie above did not succesfully execute, continue with these lines of code. 
			} else
				
				//Store error message in this variable. No output to users.
				$errors[] = "Uh oh! Could not find your username";

		//This catch stament is attempting to catch botch exeptions and errors by adding a catch block for exception after catching throwable first, "$e" [11] . Stores error message in variable and gets concatenated with the error handler. It gets called but knows nothing about $dbh->errorCode.
		} catch (PDOException $e) {
			$errors[] = "Uh oh! There was an error connecting to the database. Please try again later. Error code " . $dbh->errorCode();
		}
		
	}
?>
<!doctype html>

<html lang="en">

	<!--  Will check if the head.php file has already been included, and if so, not include (require) it again. -->
	<?php require_once('include/head.php');?>

	<body>
		
		<!-- 	Will check if the header.php file has already been included, and if so, not include (require) it again	 -->		
		<?php require_once('include/header.php');?>
		
		<h3>Forgot Password</h3>

		<!-- Form for password reset, and display securty questions for them to answer to verify it is them.		 -->
		<!-- TODO: There's no need to refer to assigne "required" to itself. Just add required. -->
		<form method="post" action="reset.php">
			<input type="text" name="username" id="username" placeholder="Enter your username" required="required" size="40" value="<?php echo $username;?>" <?php if(isset($question)) { echo "readonly"; } ?>
			<br>
			<?php 
			//isset — Determine if a the security question above is declared and is different than NULL [12].
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
				// Attempting to compare security question's answer it the "correct" answer from the database.  If so, a password field is shown to them to possibly add another password for the user. It's intention is to reconfigure old password to new. 
				//TODO: Password field should have type "password".
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
		//If errors exists, display the following blocks of code. 
		if (count($errors) > 0) { ?>
			<div class="error">
				<ul>
				<?php 
				// These lines intend to display the amount of errors that has occured. Not good practice.
				for($i = 0; $i < count($errors); $i++) { ?>
					<?php echo $errors[$i]; ?>
				<?php } ?>
				<ul>
			</div>
					
<?php /* 
Sources:
[1](http://www.peachpit.com/articles/article.aspx?p=674688&seqNum=4)
[2]  (https://www.php.net/manual/en/function.error-reporting.php)
[3]  (https://www.php.net/manual/en/function.error-reporting.php)
[4]  (https://www.w3schools.com/php/php_superglobals_post.asp)
[5]  (https://www.geeksforgeeks.org/php-array_key_exists-function/)
[6]  (https://www.php.net/manual/en/language.types.null.php)
[7] (https://www.quora.com/Why-do-some-PHP-programmers-use-_SERVER-REQUEST_METHOD-POST)
[8]  (https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)
[9]  (https://www.php.net/manual/en/pdostatement.fetchall.php)
[10] (https://www.php.net/manual/en/language.types.array.php)
[11] (https://www.php.net/manual/en/language.errors.php7.php)
[12] (https://www.php.net/manual/en/function.isset.php)

		
*/ ?>
	
		<?php } ?>

	</body>
</html>
