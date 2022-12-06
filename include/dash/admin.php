<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');

	$sql = "SELECT * FROM users2 WHERE user_uid='".$_SESSION["userUid"]."'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if($row['user_mod'] == 1){
?>

<!DOCTYPE html>
<html>
	<body>
		<?php include ('dash.nav.php');?>
	</body>
</html>
<?php
}else{
        header("Location: ".$link."/frontpage");
	exit();
}
?>
