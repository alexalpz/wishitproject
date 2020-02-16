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

//The "Try" statment is attempting to connect to the web server but it may throw an exception if the following block of code does not go through succesfully [6]. 
try{

	//Blocks of code attempting to connect to the web server and database. First line is connecting to web server and database and the second line is connecting as the admin using the password required to be authorized.  PDO ERRMODR attribute (controls error reporting) to raise a PDOException that tells you what went wrong when it goes wrong. [7]
	$conn_string = "mysql:host=localhost;dbname=wishit";
	$dbh= new PDO($conn_string, "phpmyadmin", "studentstudent");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//$sql performs a query, selecting all information of user with the specific email address.  The "prepare" statement is sent to the database server. During execute the client binds parameter values and sends them to the server
	//TODO: Add variable name to execution line. 
	$sql = "SELECT * FROM users where email = '$email';";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();

	//Fetch all of the remaining rows in the result set from database [8].
	//TODO: Call proper variable name once named the execution line above. 
	$records = $stmt->fetchAll();

	// If the data does not exists, continue with the following blocks of code.
	if (count($records) < 1) {

		// Continue to send a HTTP header to the server display error on login page. 
		//TODO: Add required message to exit parameter. 
		header("Location: index.php?notloggedin=true");
		exit();

	}

	//$sql variable querie that lists out all info from a "wishes" table identified by specific email address. no variable is storing the execution.
	//TODO: Create variable name for execution. 
	$sql = "SELECT * FROM wishes where email = '$email';";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();

	//The $user variable returns an array containing all of the remaining rows in the result set [9]. 
	$wishes = $stmt->fetchAll();

//This catch stament is attempting to catch botch exeptions and errors by adding a catch block for exception after catching the throwable first "$e" [10] . It stores error message in variable and the error handler gets called but knows nothing about $dbh->errorCode.
}catch(Exception $e){
	$errors[] = "There was an error connecting to the database. Please try again later. Error code " . $dbh->errorCode();
}




?>

<!doctype html>
<html lang="en">

	<?php require_once('include/head.php');?>

	<body>
		
		<!--  Will check if the header.php file has already been included, and if so, not include (require) it again. -->
		<!--TODO: Will fix the position of table tags and correctly format it in order -->
		<?php require_once('include/header.php');?>
		<table>
			<tr>				
				<h3>Current Wishes:</h3>
			</tr>			
	
			<?php 
			//If no rows are found in the wishes table, the code intends to display an error message
			//TODO: Error message is not outputting correctly. Should fix this. 
			if (sizeof($wishes) == 0) { ?>
				No wish list found.
			
			<?php 
			//If rows of data are found, continue with the following lines of code. 
			//TODO: Add a table tag to the table headers. Add <td> tags for data. Would not seperate closing and starting tags in a seperate PHP line. Join them together. 
			} else { 	
			?>
			<tr>
				<th>Wish </th>
				<th>Image </th>												
				<th>Delete </th>																								
			</tr>

			<tr>
				<?php 
					// foreach ($wishes as $wish) creates just one database query[11]. It intends to store wishid, description, url and image file. 
					// TODO: Remove seperating PHP and just join them together with the lines of code it hopes to execute. Do not open in one and continue with closing tag in another PHP. Bad practice. 
					foreach ($wishes as $wish) { ?>
					<?php 

						// Storing data from arrays in database. 
						$wishid = $wish['wishid'];
						$description = $wish['description'];
						$url = $wish['url'];
						$imagefile = $wish['imagefile'];
					?>
						<!--Form intended to grab users information. Form is never displayed to the user. The user cannot change the value of the field, or interact with it. [12]-->
						<form method="POST" action="delete.php" style="display: inline-block">
							<input type="hidden" name="wishid" value="<?php echo $wishid;?>" >
														
							<td> <a href="<?php echo $url; ?>" target="_blank" id="url"><?php echo $description; ?></a> </td>

							<?php 
								//!empty() will will accept any arguments. This function generate warning when variable does not exists [13]. Attempting to upload a file in regards to the specific user.  

								if(!empty($imagefile)) { ?>
									<td><a href="<?php echo "uploads/$imagefile"; ?>"><img src="<?php echo "thumb/$imagefile"; ?>"></a></td>
								<?php } ?>
									
							<input type="hidden" name="wishid" value="<?php echo $wishid;?>" >
							<td><input type="submit" name="submitDelete" value="&times;" ></td>
						</form>
				</tr>
				<?php } ?>
				
			<?php } 
				//This code seems to only hold the closing tag of previous block of code. Bad practice.
				//TODO: Join closing tag to it's original line of code. 
			?>	
		</table>
		<hr>

		<!-- Table intended to store application's items.  -->
		<!--TODO: Would fix all the formating involed in this section regarding the forms and table tags-->
		<table>
			<tr>				
				<h3>New wishlist item:</h3>
			</tr>
			<tr>
				<th>Description </th>						
				<th>URL </th>
				<th>Add </th>																							
			</tr>
			<tr>
				<!-- 	Form intends to accept input from user regarding an image file -->
				<!-- TODO: Change url type to "url" -->
				<form  method="POST" action="add.php" enctype="multipart/form-data">
					<input type="hidden" name="email" value="<?php echo $email;?>">
					<td><input type="text" name="description" size="50"></td>
					<td><input type="text" name="url" size="50"></td>
					<td><input type="file" name="myimage" id="myimage"></td>
					<td><input type="submit" value="&#43;"></td>
				</form>
			</tr>
		</table>
					
			<?php 
			//array_key_exists- Checks if the given key or index exists in the array [14]. If key in array is "error" contain the error message in variable. 
			if (array_key_exists('error', $_GET)) {

				//array_key_exists- Checks if the given key or index exists in the array [14]. If key in array is "add" contain the error message in variable. Error message is not being outputted. 
				if ($_GET['error'] == 'add') { 
					$errors[] = "Uh oh! There was an error adding your wish item. Please try again later.";
				} 
				
				//array_key_exists- Checks if the given key or index exists in the array [14]. If key in array is "delete" contain the error message in variable. Error message is not being outputted. 
				if ($_GET['error'] == 'delete') { 
					$errors[] = "Uh oh! There was an error deleting your wish item. Please try again later.";
				}
			} 

			// If the amount of intended "errors" exists then display following codes . 
		//TODO: Add missing "div" end tag. Fix PHP positions. End tag is in the beginning of line of code. Starting tag is elsewhere. 
			if (count($errors) > 0) { ?>
				<div class="error">
					<?php 
						//Lines of PHP intending to output the amount of "errors" stored in variable. There's no need display amount of errors to users.    										 
						for($i = 0; $i < count($errors); $i++) {
							echo $errors[$i];
						} ?>
					
					
<?php /*
Sources: 
[1] https://www.w3resource.com/php/function-reference/isset.php
[2] https://www.php.net/manual/en/function.setcookie.php
[3] https://www.base64decoder.io/php/
[4] https://my.bluehost.com/hosting/help/241
[5] https://www.w3schools.com/php/func_misc_exit.asp
[6] https://stackify.com/php-try-catch-php-exception-tutorial/
[7] https://www.quora.com/What-is-SetAttribute-PDO-ATTR_ERRMODE-PDO-ERRMODE_EXCEPTION-in-PHP
[8] https://www.php.net/manual/en/pdostatement.fetchall.php
[9] https://www.php.net/manual/en/pdostatement.fetchall.php\
[10]https://www.php.net/manual/en/language.errors.php7.php
[11]https://stackoverflow.com/questions/43322413/laravel-php-foreachuserall-as-user-performance
[12]https://html.com/input-type-hidden/
[13]https://www.geeksforgeeks.org/why-to-check-both-isset-and-empty-function-in-php/
[14]https://www.php.net/manual/en/function.array-key-exists.php
					
					
					
*/ ?>
				</div>
			<?php } ?>
		<hr>
	</body>
</html>
