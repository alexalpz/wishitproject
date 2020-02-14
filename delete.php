<?php

//
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//
$dbconnecterror = FALSE;
$dbh = NULL;

//
try{

	//
	$conn_string = "mysql:host=localhost;dbname=wishit";
	$dbh= new PDO($conn_string, "root", "password");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//
	if ($_SERVER['REQUEST_METHOD'] == "POST") {

		//
		$wishid = $_POST['wishid'];

		//
		if(!empty($_POST['description'])){
			$description = $_POST['description'];
		}
		
		//
		if(!empty($_POST['description'])){
			$url = $_POST['url'];
		}
		
		//
		if(!empty($_POST['email'])){
			$email = $_POST['email'];
		}			
		
		//
		$sql = "DELETE FROM wishes where wishid = '$wishid'";			
		$stmt = $dbh->prepare($sql);
		$success = $stmt->execute();


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