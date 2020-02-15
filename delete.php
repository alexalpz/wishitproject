<?php

//The first line tells PHP you'd like to see any errors that occur [1]. The second line will determine if the errors will be displayed or hidden to the user. The third line "error_reporting" sets which PHP errors are reported[2]. "E_ALL" reports all PHP errors[3].
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Setting "dbconnecterror" variable to “false” won't display anything.  "dbh" is placeholder variable that will connect to the webserver. 
$dbconnecterror = FALSE;
$dbh = NULL;

//All of the code within the try block is ran until an exception is potentially thrown [4]
try{

	//First line is connecting to web server and database and the second line is connecting as the admin using the password required to be authorized.  PDO ERRMODE attribute (controls error reporting) to raise a PDOException that tells you what went wrong when it goes wrong. [5]
	$conn_string = "mysql:host=localhost;dbname=wishit";
	$dbh= new PDO($conn_string, "phpmyadmin", "studentstudent");
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	//This determines whether the request was a POST or GET request. [6]
	if ($_SERVER['REQUEST_METHOD'] == "POST") {

		//An associative array passing variable "$wishid" to the current script via the HTTP POST method [7].
		$wishid = $_POST['wishid'];

		//!empty() will will accept any arguments. This function generate warning when variable does not exists [8]. The $_POST variable is being used to create an associative array with an access key called "description"[9]. Data gets received at server end.  The "else" statment stores message implying no description was entered, may be counterproductive from !empty() method. No output from either "if" or "else" statement. 
		if(!empty($_POST['description'])){
			$description = $_POST['description'];
		}
		
		//Similar to code above. !empty() will accept any arguments. No warning if variable does not exists. $_POST creates associative key called "url". Data is received at server end. The "else" stament would store an error message but no output. 
		if(!empty($_POST['description'])){
			$url = $_POST['url'];
		}
		
		//Similar to code above. !empty() will will accept any arguments. No warning if variable does not exists. $_POST creates associative key called "email". Data is received at server end. The "else" stament would store an error message but no output also. 		
		if(!empty($_POST['email'])){
			$email = $_POST['email'];
		}			
		
		//"$sql" variable storing query to delete whatever ID had been entered. The "prepare" statement is sent to the database server[8].During execute the client binds parameter values and sends them to the server [8].
				
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

/*Sources:
[1]  (http://www.peachpit.com/articles/article.aspx?p=674688&seqNum=4)
[2]  (https://www.php.net/manual/en/function.error-reporting.php)
[3]  (https://www.php.net/manual/en/function.error-reporting.php)
[4]  (https://www.datacamp.com/community/tutorials/exception-handling-python)
[5]  (https://www.quora.com/What-is-SetAttribute-PDO-ATTR_ERRMODE-PDO-ERRMODE_EXCEPTION-in-PHP)
[6]  (https://www.quora.com/Why-do-some-PHP-programmers-use-_SERVER-REQUEST_METHOD-POST)
[7]  (https://www.php.net/manual/en/reserved.variables.post.php)
[8]  (https://www.geeksforgeeks.org/why-to-check-both-isset-and-empty-function-in-php/)
[9]  (https://www.ostraining.com/blog/coding/retrieve-html-form-data-with-php/)
[10] (https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)


*/
?>
