<?php

//The isset() function return false if testing variable contains a NULL value [1]. The value in this case contains the value of a cookie [2].


if (isset($_COOKIE['wishit_session_id'])) {

	//The $email variable stores built in function "Base64" to grab the encoded cookie value, if it exists, and returns the decoded data, if it has been encoded [3].  
	$email = base64_decode($_COOKIE['wishit_session_id']);

} else {

	//This attempts to store a HTTP header of a unlogged page due to missing cookie value but redirects do no work due to PHP variables, in this case "?notloggedin=true" [4]. The "exit" function is missing a required message to print before terminating the script [5].
	//TODO: Add some form of exit message to "exit" function. 
	header("Location: index.php?notloggedin=true");
	exit();
	
}

// The purpose of this function is to check whether the request was done via GET method [6].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	
	//The "array_key_exists" checks if the given key or index exists in the array. The "error" string specifies the key, and the $_GET specifies which array [7].
	if(array_key_exists("error", $_GET)){
		
		//$_GET syntax is being used but there is no form in this file that contains the GET Method [8]. It's also comparing the key value to a string. Not entirely sure when 'db' would be be entered as data, this block of code may not be executed. 
		if ($_GET['error'] == 'db') {
			$errors[] = "Uh oh! There was a problem with the database.";
		}
	}
}

//The "Try" statment is attempting to connect to the web server but it may throw an exception if the following block of code does not go through succesfully [9]. 
try{

	//Blocks of code attempting to connect to the web server and database. First line is connecting to web server and database and the second line is connecting as the admin using the password required to be authorized.  PDO ERRMODR attribute (controls error reporting) to raise a PDOException that tells you what went wrong when it goes wrong. [10]
	$conn_string = "mysql:host=localhost;dbname=wishit";
	$dbh= new PDO($conn_string, "phpmyadmin", "studentstudent");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	// $sql variable stores querie to list any user with particular email, execution is not in a variable to be stored. 
	//TODO: Add variable name to execution line. 
	$sql = "SELECT * FROM users where email = '$email';";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();

	//Fetch all of the remaining rows in the result set from database [11], but $stmt variable isn't the right variable to call here. 
	//TODO: Call proper variable name once named the execution line above. 
	$records = $stmt->fetchAll();

	// If the data does not exists, continue with the following blocks of code.
	if (count($records) < 1) {

		// Continue to send a HTTP header to the server display error on login page.  
		header("Location: index.php?notloggedin=true");
		exit();

	}

	//$sql variable storing querie that lists out all info from a "user" table, no variable is storing the execution so it may not be executed. 
	$sql = "SELECT * FROM users;";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();

	//The $user variable returns an array containing all of the remaining rows in the result set [12]. 
	$users = $stmt->fetchAll();

//This catch stament is attempting to catch botch exeptions and errors by adding a catch block for exception after catching the throwable first "$e" [13] . It then plans to send an HTTP header displaying error in the application while also trying to concatenate with the last operation on the database handle. var_dump dumps information about a variable [14].
}catch(Exception $e){
	$errors[] = "There was an error connecting to the database. Please try again later. Error code " . $dbh->errorCode();
	var_dump($e);
}




?>

<!doctype html>
<html lang="en">

	<?php require_once('include/head.php');?>

	<body>
		
		<?php require_once('include/header.php');?>

			<?php 
			//  sizeof() simply returns the amount of memory is allocated to that data types [15]. The text in the code is not stored in a variable nor is it being echoed. 
		//TODO: Echo the "No users list found" text.
			?>
			<?php 
			if (sizeof($users) == 0) { ?>
				No users list found.
			
			<?php 
			// There is no block of code inside this else statement to run. 
				//TODO: Add some code to the else statement for when a user IS found I pressume. 
			} else { 	
			?>
			
			<?php 
			//    There is no block of code in between these opening and closing tags of php.
			?>
			
			<!-- A table filled with table headers and no table data where the inputs may go. Table has no closing tag.   -->  <!--TODO: Close table. may need to adjust table for data input-->
			
				<table>
					<tr>
						<th>Email </th>						
						<th>Username </th>
						<th>Password </th>
						<th>Security Question </th>														
						<th>Security Answer </th>		
						<th>Is Admin </th>														
						<th>Save </th>														
						<th>Delete </th>																										
												
					</tr>
				<?php 
					// foreach ($users as $user) creates just one database query[16]. There seems to be a missing curly bracket as well as a line of code for it. May not work at all. The rest of the variables after it intend to store email,username,password...etc, into an array called $user. 
					foreach ($users as $user) { ?>
					<?php 
						$email = $user['email'];
						$username = $user['username'];
						$password = $user['password'];
						$securityquestion = $user['securityquestion'];
						$securityanswer = $user['securityanswer'];
						$isadmin = $user['isadmin'];
						
					?>

						<!--POST means the browser will send the data to the web server to be processed [17].	Form contains input lines in form of table. Input has specific requirements in order for it to be submitted. Inputs did not have "required" but had placed them on username and password as requested. Values store php lines where their value name will be printing --> 
					<!--TODO: Add missing semicolon on style. add missing "table" tag on table before adding tr tag. Form inside tr tag, bad practice. Change email to "email" type, password to "password" type. -->
						<form method="POST" action="adminhandler.php?isadmin=true" style="display: inline-block">
							<tr>
								
							<input type="hidden" name="operation" value="edit">
							<td><input type="text" 	 name="email" id="email" size="50" value="<?php echo $email;?>" maxlength="100" readonly></td>
							<td><input type="text" 	required  name="username" id="username" size="50" value="<?php echo $username;?>" maxlength="100" ></td>
							<td><input type="text" 	 required name="password" id="password" size="50" value="<?php echo $password;?>" maxlength="100" ></td>
							<td><input type="text" 	 name="securityquestion" id="securityquestion" size="50" value="<?php echo $securityquestion;?>" maxlength="100" ></td>
							<td><input type="text" 	 name="securityanswer" id="securityanswer" size="50" value="<?php echo $securityanswer;?>" maxlength="100" ></td>
							<td><input type="text" 	 name="isadmin" id="isadmin" size="5" value="<?php echo $isadmin;?>" maxlength="1" ></td>
							<td><input type="submit" name="submitEdit" id="submitedit" value="&check;" ></td>
							
						</form>

						<!-- POST means the browser will send the data to the web server to be processed [17]. Unsure why both fields are hidden for or what it's purpose in the file is.  -->
					<!--TODO: Add missing semicolon on style. Remove form from tr tag. -->
						<form method="POST" action="adminhandler.php?isadmin=true" style="display: inline-block">
							<input type="hidden" name="operation" value="delete">
							<input type="hidden" name="email" value="<?php echo $email;?>">
							<td><input type="submit" name="submitDelete" id="submitdelete" value="&times;" ></td>
						</form>
						
						</tr>

				<?php } ?>
				
			<?php } ?>	

			<?php 
				//array_key_exists- Checks if the given key or index exists in the array [18]. If key in array is "add" contain the error message in variable. The variable is not outputting anything. 
				if (array_key_exists('error', $_GET)) { ?>
				<?php if ($_GET['error'] == 'add') { 
					$errors[] = "Uh oh! There was an error adding your wish item. Please try again later.";
				} ?>
				<?php 
				//$_GET is a PHP super global variable which is used to collect form data after submitting an HTML form with method="get" [19]. If key in array is "delete" contain the error message in a variable. The variable is not outputting anything. 
				if ($_GET['error'] == 'delete') { 
					$errors[] = "Uh oh! There was an error deleting your wish item. Please try again later.";
				}
			} 

			// If the amount of intended "errors" are greater than zero than display a class "error" from css file.  Does not seem practical.
		//TODO: Add missing div end tag. PHP start and end tag are reversed, fix their positions or simply not add them at all since div is html related. 
			if (count($errors) > 0) { ?>
				<div class="error">
					<?php 
						//  Lines of PHP indenting to output the amount of "errors" stored in variable. There's no need display amount of errors to users. 									 
						for($i = 0; $i < count($errors); $i++) {
							echo $errors[$i];
							
							
							/*Sources:
							 [1] https://www.w3resource.com/php/function-reference/isset.php
							 [2] https://www.php.net/manual/en/function.setcookie.php
							 [3] https://www.base64decoder.io/php/
							 [4] https://my.bluehost.com/hosting/help/241
							 [5] https://www.w3schools.com/php/func_misc_exit.asp
							 [6] https://www.sitepoint.com/community/t/if--server-request-method-post-vs-isset-submit/252336/5				      
							 [7] https://www.w3schools.com/php/func_array_key_exists.asp
							 [8] http://shodor.org/~kevink/phpTutorial/nileshc_getreqpost.php
							 [9] https://stackify.com/php-try-catch-php-exception-tutorial/
							 [10]https://www.quora.com/What-is-SetAttribute-PDO-ATTR_ERRMODE-PDO-ERRMODE_EXCEPTION-in-PHP
							 [11]https://www.php.net/manual/en/pdostatement.fetchall.php
							 [12]https://www.php.net/manual/en/pdostatement.fetchall.php
							 [13]https://www.php.net/manual/en/language.errors.php7.php
							 [14]https://www.php.net/manual/en/function.var-dump.php
							 [15]https://www.geeksforgeeks.org/sizeof-operator-c/
							 [16]https://stackoverflow.com/questions/43322413/laravel-php-foreachuserall-as-user-performance
							 [17]https://html.com/attributes/form-method/
							 [18]https://www.php.net/manual/en/function.array-key-exists.php
							 [19]https://www.w3schools.com/php/php_superglobals_get.asp
							 
							 */
						} ?>
				</div>
			<?php } ?>
		</table>
	</body>
</html>
