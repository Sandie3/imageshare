<?php
	$php_root = $_SERVER['DOCUMENT_ROOT'];
	include ($php_root.'/include/head.php');
	include ($php_root.'/include/conf.php');
	include ($php_root.'/include/nav.php');

	$sql = "SELECT * FROM users2";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if($row['user_mod'] == 1){
?>

<!DOCTYPE html>
<html lang="en">
	<link rel="stylesheet" href="<?php echo $link ?>/style/userlist.css">
	<body>
		<?php include ('dash.nav.php');?>
		<div id="contents">
			<table>
				<tr>
					<th>ID</th>
					<th>User</th>
					<th>Post</th>
				</tr>

				<?php
					// Populate user list
					$sql = "SELECT * FROM posts ORDER BY posts_id DESC";
					$result = $conn->query($sql);

					if ($result->num_rows <= 0) {
						echo'Nothing here. Did the db fail?';
						exit();
					}

					// Repeat fetch to see all items
					while($row = $result->fetch_assoc()) {
				?>

					<tr>
						<td><?php echo $row["posts_id"] ?></td>
						<td><a href="<?php echo $link ?>/profile?id=<?php echo $row["user_uid"]?>"><?php echo $row["user_uid"]?></a></td>
						<td><?php echo $row["post"]?></td>
					</tr>
				<?php
					}

					$conn->close();
				?>
				</div>
			</table>
		</div>
	</body>
</html>
<?php
}else{
	header("Location: http://62.210.219.75/frontpage");
}
?>
