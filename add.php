<?php
//
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//
$dbconnecterror = FALSE;
$dbh = NULL;
$fail = NULL;


	//
	try{

		//
		$conn_string = "mysql:host=localhost;dbname=wishit";
		$dbh= new PDO($conn_string, "root", "password");
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		//
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			
			//
			if(!empty($_POST['description'])){
				$description = $_POST['description'];
				
			}else{
				$fail = "empty description";
			}
			
			//
			if(!empty($_POST['url'])){
				$url = $_POST['url'];
			}else{
				$fail = "empty url";
			}	
			
			//
			if(!empty($_POST['email'])){
				$email = $_POST['email'];
			}else{
				$fail = "empty email";
			}			
			
			//
			if(empty($fail)){

				//
				$sql = "INSERT INTO wishes (email, description, url) " .
					"VALUES ('$email', '$description', '$url')";			
				$stmt = $dbh->prepare($sql);
				$success = $stmt->execute();

				//
				if ($success) {
					
					//
					$last_id = $dbh->lastInsertId();

					//
					$imagename = $_FILES["myimage"]["name"];
					$imageFileType = strtolower(pathinfo($imagename, PATHINFO_EXTENSION));
					$target_dir = "uploads/";
					$target_name = $last_id . "." . $imageFileType;
					$target_file = $target_dir . $target_name;

					//
					//if (!move_uploaded_file($_FILES["myimage"]["tmp_name"], $target_file)) {
					//	header("Location: wishlist.php?error=image");
					//}

					//
					$sql = "UPDATE wishes SET imagefile = '$target_name' WHERE wishid = '$last_id'";			
					$stmt = $dbh->prepare($sql);
					$success = $stmt->execute();
					
					//
					/* 
					sudo apt-get install imagemagick
					*/
					$cmd = "convert $target_file -resize 50x50 thumb/$target_name";
					//exec($cmd);
				}

			}
				
			//
			if ($success) {
				header("Location: wishlist.php");	


			//
			} else {
				header("Location: wishlist.php?error=add");
			}
			
		//
		}else{
			header("Location: wishlist.php");	
		}

	//	
	}catch(Exception $e){
		header("Location: wishlist.php?error=db");
	}

		
	


?>