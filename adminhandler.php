<?php

//
if (isset($_COOKIE['wishit_session_id'])) {

	//
	$loggedinemail = base64_decode($_COOKIE['wishit_session_id']);
}

//	
try{

	//
	$conn_string = "mysql:host=localhost;dbname=wishit";
	$dbh= new PDO($conn_string, "root", "password");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		//
		if ($_GET['isadmin']!= 'true'){
			header("Location: index.php");
			exit();
		};

		//
		if($_POST['operation']=='edit'){

			//
			$email = $_POST['email'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$securityquestion = $_POST['securityquestion'];
			$securityanswer = $_POST['securityanswer'];
			$isadmin = $_POST['isadmin'];
						
			//
			$sql = "UPDATE users SET username = '$username', password = '$password', securityquestion = '$securityquestion', securityanswer = '$securityanswer', " . 
				"isadmin = '$isadmin' WHERE email = '$email';";
			$stmt = $dbh->exec($sql);
			
		//
		}else if($_POST['operation']=='delete'){

			//
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
	
	
?>