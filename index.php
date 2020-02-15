<?php
/*
Note: Be sure to set file ownership/permissions after creating or modifying files.
sudo chown -R www-data: /var/www/html
sudo chmod -R g+w /var/www/html/uploads
sudo chmod -R g+w /var/www/html/thumb
*/

//The first line tells PHP you'd like to see any errors that occur [1]. The second line will determine if the errors will be displayed or hidden to the user. The third line "error_reporting" sets which PHP errors are reported[2]. "E_ALL" reports all PHP errors[3].
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Setting "dbconnecterror" variable to “false” won't display anything.  "dbh" is placeholder variable that will connect to the webserver. 
$dbconnecterror = FALSE;
$dbh = NULL;

//Two variables with empty strings. 
$username = "";
$password = "";

//This variable is attempting to create an array but has nothing in it's parameters.
$errors = array();

//This determines whether the request was a POST or GET request, in this case it is GET [4]
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	
	//The "array_key_exists" checks if the given key or index exists in the array. The "error" string specifies the key, and the $_GET specifies which array [5]. setcookie() defines a cookie to be sent along with the rest of the HTTP headers [6]. Sets the experation date to one hour ago. 
	if(array_key_exists("logout", $_GET)){
		setcookie("wishit_session_id", "", time() - 3600);
		setcookie("wishit_isadmin", "", time() - 3600);
	}
}

//This determines whether the request was a POST or GET request, in this case it is POST [4]
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	//Variables passed to the current script via  HTTP post method [7]. The script is require user input to store the following information into database.
	$username = $_POST['username'];
	$password = $_POST['password'];

	try{
		//Blocks of code attempting to connect to the web server and database. First line is connecting to web server and database and the second line is connecting as the admin using the password required to be authorized.  PDO ERRMODR attribute (controls error reporting) to raise a PDOException that tells you what went wrong when it goes wrong. [8].
		$conn_string = "mysql:host=localhost;dbname=wishit";
		$dbh= new PDO($conn_string, "phpmyadmin", "studentstudent");
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		//"$sql" variable storing query listing all information of user with set username and password as a search method in database.  The "prepare" statement is sent to the database server.During execute the client binds parameter values and sends them to the server. Fetch all of the remaining rows in the result set from database [9].
	//TODO: Execution line should have it's own variable to call. 
		$sql = "SELECT * FROM users where username = '$username' AND password = '$password';";
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		$records = $stmt->fetchAll();

		// If the data does not exists, continue with the following blocks of code.
		if (count($records) > 0) {

			//Recursive and multi-dimensional arrays stored in variables [10].
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
<?php 
/*Sources:
[1]  (http://www.peachpit.com/articles/article.aspx?p=674688&seqNum=4)
[2]  (https://www.php.net/manual/en/function.error-reporting.php)
[3]  (https://www.php.net/manual/en/function.error-reporting.php)
[4]  (https://www.quora.com/Why-do-some-PHP-programmers-use-_SERVER-REQUEST_METHOD-POST)
[5]  (https://www.w3schools.com/php/func_array_key_exists.asp)
[6]  (https://www.php.net/manual/en/function.setcookie.php)
[7]  (https://www.php.net/manual/en/reserved.variables.post.php)
[8]  (https://www.quora.com/What-is-SetAttribute-PDO-ATTR_ERRMODE-PDO-ERRMODE_EXCEPTION-in-PHP)
[9]  (https://www.php.net/manual/en/pdostatement.fetchall.php)
[10] (https://www.php.net/manual/en/language.types.array.php)
					
*/

?>
		<?php } ?>
		
		
	</body>
</html>
