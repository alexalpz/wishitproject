<?php 

//
if (isset($_COOKIE['wishit_session_id'])) {

	//
	$target = "wishlist.php";

//
}else{

	//
	$target = "index.php";
}
?>

<!-- 		 -->
<a href="<?php echo $target;?>" id="logo">
	<h1 id="pageTitle">
		<img src="images/logo.svg" alt="genie wish lamp" height="42" width="42">
		wishIT
	</h1>
</a>

<!-- 		 -->
<a class="loginLinks" href="index.php?logout=true"><img src="images/logout.jpeg" alt="logout" title="logout"></a>

<?php 
//
if (isset($_COOKIE['wishit_isadmin'])) {
?>

	<!-- 		 -->
	<a class="loginLinks" href="admin.php?logout=true"><img src="images/admin.jpeg" alt="admin" title="admin"></a>

<?php } ?>

<hr>
		
