<?php

//The first line tells PHP you'd like to see any errors that occur [1]. The second line will determine if the errors will be displayed or hidden to the user. The third line "error_reporting" sets which PHP errors are reported[2]. "E_ALL" reports all PHP errors[3].

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Setting "dbconnecterror" variable to “false” won't display anything.  "dbh" is placeholder variable that will connect to the webserver. "fail" variable is a placeholder called for when required input data is missing. 


$dbconnecterror = FALSE;
$dbh = NULL;
$fail = NULL;


	//All of the code within the try block is executed until an exception is potentially thrown. The code within the catch statement must handle the exception that was thrown.
	try{

		// First line is connecting to web server and database and the second line is connecting as the admin using the password required to be authorized.  PDO ERRMODE attribute (controls error reporting) to raise a PDOException that tells you what went wrong when it goes wrong. [4]
		$conn_string = "mysql:host=localhost;dbname=wishit";
		$dbh= new PDO($conn_string, "phpmyadmin", "studentstudent");
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		 
		//This determines whether the request was a POST or GET request. [5]
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			
			// !empty() will will accept any arguments. This function generate warning when variable does not exists [6]. The $_POST variable is being used to create an associative array with an access key called "description"[7]. Data gets received at server end.  The "else" statment stores message implying no description was entered, may be counterproductive from !empty() method. No output from either "if" or "else" statement. 
			
			if(!empty($_POST['description'])){
				$description = $_POST['description'];
				
			}else{
				$fail = "empty description";
			}
			
			//Similar to code above. !empty() will accept any arguments. No warning if variable does not exists. $_POST creates associative key called "url". Data is received at server end. The "else" stament would store an error message but no output. 
			if(!empty($_POST['url'])){
				$url = $_POST['url'];
			}else{
				$fail = "empty url";
			}	
			
			//Similar to code above. !empty() will will accept any arguments. No warning if variable does not exists. $_POST creates associative key called "email". Data is received at server end. The "else" stament would store an error message but no output also. 
			//All "if" statements seem to be sharing the same "fail" variable. 
			//TODO: Should differentiate each "if" statement's "else" variable to not receive incorrect outputs. 
			
			if(!empty($_POST['email'])){
				$email = $_POST['email'];
			}else{
				$fail = "empty email";
			}			
			
			//empty method will check whether the "$fail" variable is empty. the !empty method above made sure it did have a varible. This whole stament may not be used.
			if(empty($fail)){

				//"$sql" variable concatenates two query strings, one involving previous variables that took input from somewhere (If anywhere at all).  A "wishes" table is implied to exists. The "prepare" statement is sent to the database server[8].During execute the client binds parameter values and sends them to the server [8].
				//TODO: Join querie strings together. Remove quotation marks on variables. Would add only a single querie in "prepare" parameters.
				
				$sql = "INSERT INTO wishes (email, description, url) " .
					"VALUES ('$email', '$description', '$url')";			
				$stmt = $dbh->prepare($sql);
				$success = $stmt->execute();

				// If the query above is executed succesfully, run this "IF..." statement. Code above may give issues.
				if ($success) {
					
					//Gets the ID of the last inserted row by using the lastInsertId method from the web server[9].  
					$last_id = $dbh->lastInsertId();

					// $imagename contains the name of the file that was uploaded[10]. The $imageFileType variable will return information about the directoryname path using pathinfo() function[11]. The PATHINFO_EXTENSION only returns the last extension if the path has more than one extension[12]. $target_dir = "uploads/" tells the server where to put the uploaded file [13]. $target_name seems to be trying to concatenate a row's id with a file extension, joining them with a string. $target_file tries to concatenate the file path along with the name of the new file using a row's id, doesn't seem practical. 
					$imagename = $_FILES["myimage"]["name"];
					$imageFileType = strtolower(pathinfo($imagename, PATHINFO_EXTENSION));
					$target_dir = "uploads/";
					$target_name = $last_id . "." . $imageFileType;
					$target_file = $target_dir . $target_name;

					//move_uploaded_file — Moves an uploaded file to a new location [14]. File will be stored in temporary location when using tmp_name instead of name [15]. If the server sends a correct redirection header, the browser redirects and  changes the url. May bring browser issues[16]. 
					//TODO: For the header, it requires an absolute URI as argument to use "Location:" including the scheme, hostname and absolute path[16]. 
					
					//if (!move_uploaded_file($_FILES["myimage"]["tmp_name"], $target_file)) {
					//	header("Location: wishlist.php?error=image");
					//}

					//This block of code is attempting to reconfigure the latest file handle once it has been modified. 
					$sql = "UPDATE wishes SET imagefile = '$target_name' WHERE wishid = '$last_id'";			
					$stmt = $dbh->prepare($sql);
					$success = $stmt->execute();
					
					//There's a  package installation for ImageMagick that would be considered a syntax error in this code.  The $cmd variable is holding a string that looks like it wants to use a mix of php and commands that would not work anywhere. 
					//TODO: Remove this package installation from file. Remove "$cmd" variable and it's execution command.
					/* 
					sudo apt-get install imagemagick
					*/
					$cmd = "convert $target_file -resize 50x50 thumb/$target_name";
					//exec($cmd);
				}

			}
				
			//Without any specified conditions about what the "$success" variable should be or have, we send a raw HTTP header to the server[17]. This redirects the browser to the wishit application.
			if ($success) {
				header("Location: wishlist.php");	


			//Sends an HTTP header to the server that displays there was an error with the wishit application. 
			} else {
				header("Location: wishlist.php?error=add");
			}
			
		//Sends an HTTP header with the same location as the "If" statement, regardless if the wishit application's intended querie commands worked or not. Seems unpractical. 
		}else{
			header("Location: wishlist.php");	
		}

	//This catch stament is attempting to catch botch exeptions and errors by adding a catch block for exception after catching throwable first, "$e" [18] . It then plans to send an HTTP header displaying error in the application.
	}catch(Exception $e){
		header("Location: wishlist.php?error=db");
	}

/*Sources:
[1]  (http://www.peachpit.com/articles/article.aspx?p=674688&seqNum=4)
[2]  (https://www.php.net/manual/en/function.error-reporting.php)
[3]  (https://www.php.net/manual/en/function.error-reporting.php)
[4]  (https://www.quora.com/What-is-SetAttribute-PDO-ATTR_ERRMODE-PDO-ERRMODE_EXCEPTION-in-PHP)
[5]  (https://www.quora.com/Why-do-some-PHP-programmers-use-_SERVER-REQUEST_METHOD-POST)
[6]  (https://www.geeksforgeeks.org/why-to-check-both-isset-and-empty-function-in-php/)
[7]  (https://www.ostraining.com/blog/coding/retrieve-html-form-data-with-php/)
[8]  (https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php)
[9]  (https://thisinterestsme.com/pdo-get-last-inserted-id/)
[10] (https://stackoverflow.com/questions/23558180/every-image-has-two-different-filename-together-tmp-name-and-filesfilen)
[11] (https://www.geeksforgeeks.org/php-pathinfo-function/)
[12] (https://www.geeksforgeeks.org/php-pathinfo-function/)
[13] (https://www.bitdegree.org/learn/php-file-upload)
[14] (https://www.php.net/manual/en/function.move-uploaded-file.php)
[15] (https://stackoverflow.com/questions/18929178/move-uploaded-file-function-is-not-working)
[16] (https://stackoverflow.com/questions/7467330/php-headerlocation-force-url-change-in-address-bar)
[17] (https://www.geeksforgeeks.org/php-header-function/)
[18] (https://www.php.net/manual/en/language.errors.php7.php)
*/
?>
