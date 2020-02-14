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
	$sql = "SELECT * FROM wishes where email = '$email';";
	$stmt = $dbh->prepare($sql);
	$stmt->execute();

	//
	$wishes = $stmt->fetchAll();

//
}catch(Exception $e){
	$errors[] = "There was an error connecting to the database. Please try again later. Error code " . $dbh->errorCode();
}




?>

<!doctype html>
<html lang="en">

	<?php require_once('include/head.php');?>

	<body>
		
		<!-- 			 -->
		<?php require_once('include/header.php');?>
		<table>
			<tr>				
				<h3>Current Wishes:</h3>
			</tr>			
	
			<?php 
			//
			if (sizeof($wishes) == 0) { ?>
				No wish list found.
			
			<?php 
			//
			} else { 	
			?>
			<tr>
				<th>Wish </th>
				<th>Image </th>												
				<th>Delete </th>																								
			</tr>

			<tr>
				<?php 
					//
					foreach ($wishes as $wish) { ?>
					<?php 

						//
						$wishid = $wish['wishid'];
						$description = $wish['description'];
						$url = $wish['url'];
						$imagefile = $wish['imagefile'];
					?>
						<!-- 			 -->
						<form method="POST" action="delete.php" style="display: inline-block">
							<input type="hidden" name="wishid" value="<?php echo $wishid;?>" >
														
							<td> <a href="<?php echo $url; ?>" target="_blank" id="url"><?php echo $description; ?></a> </td>

							<?php 
								//
								if(!empty($imagefile)) { ?>
									<td><a href="<?php echo "uploads/$imagefile"; ?>"><img src="<?php echo "thumb/$imagefile"; ?>"></a></td>
								<?php } ?>
									
							<input type="hidden" name="wishid" value="<?php echo $wishid;?>" >
							<td><input type="submit" name="submitDelete" value="&times;" ></td>
						</form>
				</tr>
				<?php } ?>
				
			<?php } 
				//
			?>	
		</table>
		<hr>

		<!-- 			 -->
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
				<!-- 			 -->
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
			//
			if (array_key_exists('error', $_GET)) {

				//
				if ($_GET['error'] == 'add') { 
					$errors[] = "Uh oh! There was an error adding your wish item. Please try again later.";
				} 
				
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
		<hr>
	</body>
</html>
