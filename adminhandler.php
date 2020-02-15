<?php

//The isset() function return false if testing variable contains a NULL value [1]. The value in this case contains the value of a cookie [2].
if (isset($_COOKIE['wishit_session_id'])) {

	////The $email variable stores built in function "Base64" to grab the encoded cookie value, if it exists, and returns the decoded data, if it has been encoded [3]. 
	$loggedinemail = base64_decode($_COOKIE['wishit_session_id']);
}

////The "Try" statment is attempting to connect to the web server but it may throw an exception if the following block of code does not go through succesfully [4]. 	
try{

	//Blocks of code attempting to connect to the web server and database. First line is connecting to web server and database and the second line is connecting as the admin using the password required to be authorized.  PDO ERRMODR attribute (controls error reporting) to raise a PDOException that tells you what went wrong when it goes wrong. [5]
	$conn_string = "mysql:host=localhost;dbname=wishit";
	$dbh= new PDO($conn_string, "phpmyadmin", "studentstudent");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//The purpose of this function is to check whether the request was done via POST method [6].
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		//$_GET is a PHP global variable which is used to collect form data after submitting an HTML form with method="get" [7]. If key in array is "true",continue to send an HTTP location header displaying "index.php".  
		//TODO: exit required message to display. Add it to parameter.
		if ($_GET['isadmin']!= 'true'){
			header("Location: index.php");
			exit();
		};

		//An associative array of variables(email,username,password...etc) passed to the current script via the HTTP POST method [8]. These variables may be associated with an edit array for future editing purposes in the server. 
		if($_POST['operation']=='edit'){

			//Variables passed to the current script via  HTTP post method [8]. The script is require user input to store the following information into database.
			$email = $_POST['email'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$securityquestion = $_POST['securityquestion'];
			$securityanswer = $_POST['securityanswer'];
			$isadmin = $_POST['isadmin'];
						
			//$sql variable storing querie that will modify username and password in regards to the user input. exec() executes an SQL statement in a single function call, returning the number of rows affected by the statement [9].
			//TODO: Would remove quoation marks on php variables. Would not concatenate the queries together. Join them. 
			$sql = "UPDATE users SET username = '$username', password = '$password', securityquestion = '$securityquestion', securityanswer = '$securityanswer', " . 
				"isadmin = '$isadmin' WHERE email = '$email';";
			$stmt = $dbh->exec($sql);
			
		//If code above is unsuccessful, run the associative array of variables passed to the current script via the HTTP POST method [8]. These variables may be for removal of data in the server. 
		}else if($_POST['operation']=='delete'){

			//Accessing the form submission email data in the PHP script
			$email = $_POST['email'];

			//
			if ($email !== $loggedinemail){

				//
				$sql = "DELETE FROM wishes WHERE email = '$email';";
				$stmt = $dbh->exec($sql);
	
				//
				$sql = "DELETE FROM users WHERE email = '$email';";
				$stmt = $dbh->exec($sql);			
			}

		}
	}

	//
	header("Location: admin.php");
	exit();

//
}catch(Exception $e){

	//
	header("Location: admin.php?error=db");
	exit();
}
	/*Sources
	[1] https://www.w3resource.com/php/function-reference/isset.php
	[2] https://www.php.net/manual/en/function.setcookie.php
	[3] https://www.base64decoder.io/php/
	[4] https://stackify.com/php-try-catch-php-exception-tutorial/
	[5] https://www.quora.com/What-is-SetAttribute-PDO-ATTR_ERRMODE-PDO-ERRMODE_EXCEPTION-in-PHP
	[6] https://www.sitepoint.com/community/t/if--server-request-method-post-vs-isset-submit/252336/5
	[7] https://www.w3schools.com/php/php_superglobals_get.asp
	[8] https://www.php.net/manual/en/reserved.variables.post.php
	[9] https://www.php.net/manual/en/pdo.exec.php
	*/
	
?>
