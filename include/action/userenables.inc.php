<?php
	// If enable submit clicked
	if(isset($_POST['submit-enables'])){
		// Check if enable is set
		if($_POST["enableNSFW"] == 1){
			$mature = '1';
		}else{
			$mature = '0';
		};

		$message = "Enables updated";
		$sql = "UPDATE users2 SET user_enable_mature='".$mature."' WHERE user_id='".$_SESSION["userId"]."'";
		mysqli_query($conn,$sql);
		return 0;
	}
?>

