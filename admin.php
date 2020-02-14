<?php

//
if (isset($_COOKIE['wishit_session_id'])) {

	//
	$email = base64_decode($_COOKIE['wishit_session_id']);

} else {

	//
	header("Location: index.php?notloggedin=true");
	exit();
	
}

//
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	
	//
	if(array_key_exists("error", $_GET)){
		
		// 
		if ($_GET['error'] == 'db') {
			$errors[] = "Uh oh! There was a problem with the database.";
		}
	}
}

//
try{

	//
	$conn_string = "mysql:host=localhost;dbname=wishit";
	$dbh= new PDO($conn_string, "root", "password");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//
	$sql = "SELECT * FROM users where email = '$email';";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();

	//
	$records = $stmt->fetchAll();

	// 
	if (count($records) < 1) {

		//
		header("Location: index.php?notloggedin=true");
		exit();

	}

	//
	$sql = "SELECT * FROM users;";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();

	//
	$users = $stmt->fetchAll();

//
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
			//    
			?>
			<?php 
			if (sizeof($users) == 0) { ?>
				No users list found.
			
			<?php 
			//
			} else { 	
			?>
			
			<?php 
			//    
			?>
			
			<!-- 			 -->
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
					// 
					foreach ($users as $user) { ?>
					<?php 
						$email = $user['email'];
						$username = $user['username'];
						$password = $user['password'];
						$securityquestion = $user['securityquestion'];
						$securityanswer = $user['securityanswer'];
						$isadmin = $user['isadmin'];
						
					?>

						<!-- 			 -->
						<form method="POST" action="adminhandler.php?isadmin=true" style="display: inline-block">
							<tr>
								
							<input type="hidden" name="operation" value="edit">
							<td><input type="text" 	 name="email" id="email" size="50" value="<?php echo $email;?>" maxlength="100" readonly></td>
							<td><input type="text" 	 name="username" id="username" size="50" value="<?php echo $username;?>" maxlength="100" ></td>
							<td><input type="text" 	 name="password" id="password" size="50" value="<?php echo $password;?>" maxlength="100" ></td>
							<td><input type="text" 	 name="securityquestion" id="securityquestion" size="50" value="<?php echo $securityquestion;?>" maxlength="100" ></td>
							<td><input type="text" 	 name="securityanswer" id="securityanswer" size="50" value="<?php echo $securityanswer;?>" maxlength="100" ></td>
							<td><input type="text" 	 name="isadmin" id="isadmin" size="5" value="<?php echo $isadmin;?>" maxlength="1" ></td>
							<td><input type="submit" name="submitEdit" id="submitedit" value="&check;" ></td>
							
						</form>

						<!-- 			 -->
						<form method="POST" action="adminhandler.php?isadmin=true" style="display: inline-block">
							<input type="hidden" name="operation" value="delete">
							<input type="hidden" name="email" value="<?php echo $email;?>">
							<td><input type="submit" name="submitDelete" id="submitdelete" value="&times;" ></td>
						</form>
						
						</tr>

				<?php } ?>
				
			<?php } ?>	

			<?php 
				//
				if (array_key_exists('error', $_GET)) { ?>
				<?php if ($_GET['error'] == 'add') { 
					$errors[] = "Uh oh! There was an error adding your wish item. Please try again later.";
				} ?>
				<?php 
				//
				if ($_GET['error'] == 'delete') { 
					$errors[] = "Uh oh! There was an error deleting your wish item. Please try again later.";
				}
			} 

			// 
			if (count($errors) > 0) { ?>
				<div class="error">
					<?php 
						//   										 
						for($i = 0; $i < count($errors); $i++) {
							echo $errors[$i];
						} ?>
				</div>
			<?php } ?>
		</table>
	</body>
</html>
