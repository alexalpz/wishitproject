<?php
//The first line tells PHP you'd like to see any errors that occur [1]. The second line will determine if the errors will be displayed or hidden to the user. The third line "error_reporting" sets which PHP errors are reported[2]. "E_ALL" reports all PHP errors[3].
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Setting "dbconnecterror" variable to “false” won't display anything.  "dbh" is placeholder variable that will connect to the webserver.
$dbconnecterror = FALSE;
$dbh = NULL;

//Variables with empty strings. No way of inputting through this unless modified and updated later.
$email = "";
$username = "";
$password = "";
$securityquestion = "";
$securityanswer = "";


//Variable attempts to create an array to whatever is in parameter. Nothing is in the parameter currently. 
$errors = array();

//This determines whether the request was a POST or GET request, in this case it is POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	//The trim() removes whitespaces and also the predefined characters from both sides of a string that is left and right [4]. $_POST is also widely used to pass variables [5]
	$email = trim($_POST['email']);
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$securityquestion = trim($_POST['securityquestion']);
	$securityanswer = trim($_POST['securityanswer']);

	//All of the code within the try block is executed until an exception is potentially thrown. 
	try{

		//First line is connecting to web server and database and the second line is connecting as the admin using the password required to be authorized.  PDO ERRMODE attribute (controls error reporting) to raise a PDOException that tells you what went wrong when it goes wrong. [6]
		$conn_string = "mysql:host=localhost;dbname=wishit";
		$dbh= new PDO($conn_string, "phpmyadmin", "studentstudent");
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//$sql variable concatenates two query strings. The "prepare" statement is sent to the database server[7].During execute the client binds parameter values and sends them to the server[7].
				//TODO: Join querie strings together. Remove quotation marks on variables. Would add only a single querie in "prepare" parameters.
		$sql = "INSERT INTO users (email, username, password, securityquestion, securityanswer) " .
			"VALUES ('$email', '$username', '$password', '$securityquestion', '$securityanswer')";			
		$stmt = $dbh->prepare($sql);
		$success = $stmt->execute();

		// If the query above is executed succesfully, run this "IF..." statement. 
		if ($success) {

			// A header intended to display a user has registered as a HTTP header. Redirect brower to said page. 
			//TODO: Add exist message to exit parameter.
			header("Location: index.php?registered=true");
			exit();
			
		//Store a error message concatenated with errorCode function. The error handler gets called but knows nothing about $dbh->errorCode. [8]
		} else {
			$errors[] = "There was a problem completing your registration. Error code " . $dbh->errorCode();
		}

	//This catch stament is attempting to catch botch exeptions and errors by adding a catch block for exception after catching throwable first, "$e" [9] . The error handler gets called but knows nothing about $dbh->errorCode. [8].
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
		//If errors exists, display the following blocks of code. 
		if (count($errors) > 0) { ?>
			<div class="error">
				The following errors occurred:
				<ul>
				<?php // These lines hope to display the amount of errors that has occured. Not good practice.   ?>
				<?php for($i = 0; $i < count($errors); $i++) { ?>
					<li><?php echo $errors[$i]; ?></li>
				<?php } ?>
				<ul>
			</div>
		<?php } ?>

		<div>

			<!-- A register form expecting data input from the users. -->
			<!-- Added required to the username and password fields-->
			<form name="register" action="register.php" method="post" onsubmit="return validateForm()">
				<input type="text" name="username" id="username" placeholder="Pick a username" required value="<?php echo $username; ?>">
				<br>
				<input type="password" name="password" id="password" placeholder="Provide a password" required value="<?php echo $password; ?>" >
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
		
		<!-- Link that redirects user to Sign in form (at the index page)-->
		<a class="loginLinks" href="index.php">Sign in?</a>

		<!-- 	A javascript file used for validating data input on the register form-->
		<script>

			//Form specified to validate the form here. 
			function validateForm() {

				//If a form field (username) is empty, this function alerts a message, and returns false, to prevent the form from being submitted.
				var username = document.forms["register"]["username"].value;
				if (username == "") {
					alert("Username must be filled out");
					return false;
				}

				//If a form field (password) is empty, this function alerts a message, and returns false, to prevent the form from being submitted.
				var password = document.forms["register"]["password"].value;
				if (password == "") {
					alert("Password must be filled out");
					return false;
				}
				
				//If a form field (email) is empty, this function alerts a message, and returns false, to prevent the form from being submitted.
				var email = document.forms["register"]["email"].value;
				if (email == "") {
					alert("Email must be filled out");
					return false;
				}

				//If a form field (security question) is empty, this function alerts a message, and returns false, to prevent the form from being submitted.
				var securityquestion = document.forms["register"]["securityquestion"].value;
				if (securityquestion == "") {
					alert("Security question must be filled out");
					return false;
				}			  

				//If a form field (security answer) is empty, this function alerts a message, and returns false, to prevent the form from being submitted.
				var securityanswer = document.forms["register"]["securityanswer"].value;
				if (securityanswer == "") {
					alert("Security answer must be filled out");
					return false;
				}				  
			  
			}
			<?php
			/*Sources:
			[1]  (http://www.peachpit.com/articles/article.aspx?p=674688&seqNum=4)
			[2]  (https://www.php.net/manual/en/function.error-reporting.php)
			[3]  (https://www.php.net/manual/en/function.error-reporting.php)
			[4]  (https://www.geeksforgeeks.org/php-trim-function/)
			[5]  (https://www.w3schools.com/php/php_superglobals_post.asp)
			[6]  (https://www.quora.com/What-is-SetAttribute-PDO-ATTR_ERRMODE-PDO-ERRMODE_EXCEPTION-in-PHP)
			[7]  (https://www.geeksforgeeks.org/why-to-check-both-isset-and-empty-function-in-php/)
			[8]  (https://www.php.net/manual/en/pdostatement.errorcode.php)
			[9]  (https://www.php.net/manual/en/language.errors.php7.php)
			https://www.w3schools.com/js/js_validation.asp
			
			
			
			
			*/
			?>
			
		</script>
		
	</body>
</html>
