<?php 

//The isset() function return false if testing variable contains a NULL value [1]. The value in this case contains the value of a cookie [2].
if (isset($_COOKIE['wishit_session_id'])) {

	//Variable storing applications url as a string if identified as a known user. 
	$target = "wishlist.php";

//If the user is not identified with cookies, show index page to log in. 
}else{

	//Variable storing the index page as a string. 
	$target = "index.php";
}
?>

<!--Regardless if the user logged in or not, the page logo will appear -->
<a href="<?php echo $target;?>" id="logo">
	<h1 id="pageTitle">
		<img src="images/logo.svg" alt="genie wish lamp" height="42" width="42">
		wishIT
	</h1>
</a>

<!-- This image will have a link for users to click on when they want to log out -->
<a class="loginLinks" href="index.php?logout=true"><img src="images/logout.jpeg" alt="logout" title="logout"></a>

<?php 
//The isset() function return false if testing variable contains a NULL value [1]. The value in this case contains the value of a cookie [2]. Assuming it's going to verify if it is the admin on the application.
if (isset($_COOKIE['wishit_isadmin'])) {
?>

	<!-- Image will have a link that will redirect them to their admin page (Profile possibliy) once they click on it 		 -->
	<!--TODO: Theres a closing that in another PHP syntax. Would just unite them together. -->
	<a class="loginLinks" href="admin.php?logout=true"><img src="images/admin.jpeg" alt="admin" title="admin"></a>

<?php } ?>

<?php 
/*Sources:
 [1] https://www.w3resource.com/php/function-reference/isset.php
 [2] https://www.php.net/manual/en/function.setcookie.php
 
 */
?>

<hr>
		
