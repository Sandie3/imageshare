<?php
	// User clicked submit settings button
	if(isset($_POST['submit-settings'])){
		// Update user sex
		if (isset($_POST['user_sex'])  && !empty($_POST['user_sex'])){
			$sql = "UPDATE users2 SET user_sex='".addslashes($_POST["user_sex"])."' WHERE user_id='".$_SESSION["userId"]."'";
			mysqli_query($conn, $sql);
			$sex = $_POST['user_sex'];

			$message = "Updated user info";
		}

		// Update user country
		if (isset($_POST['user_country'])  && !empty($_POST['user_country'])){
			$sql = "UPDATE users2 SET user_country='".addslashes($_POST["user_country"])."' WHERE user_id='".$_SESSION["userId"]."'";
			mysqli_query($conn, $sql);
			$country = $_POST['user_country'];

			$message = "Updated user info";
		}

		// Update user city
		if (isset($_POST['user_city']) && !empty($_POST['user_city'])){
			$sql = "UPDATE users2 SET user_city='".addslashes($_POST["user_city"])."' WHERE user_id='".$_SESSION["userId"]."'";
			mysqli_query($conn, $sql);
			$city = $_POST['user_city'];

			$message = "Updated user info";
		}

		return 0;
	}
?>
