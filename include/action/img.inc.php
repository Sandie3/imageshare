<?php

if(isset($_POST['img-btn'])){
	$imgTitle = mysqli_real_escape_string($conn, $_POST['filetitle']);
	$imgDesc = mysqli_real_escape_string($conn, $_POST['filedesc']);
	$imgTag = mysqli_real_escape_string($conn, $_POST['filetags']);
	
	$User = $_GET['id'];
	$imgFile = $_FILES['file'];
	
	if(!isset($_POST['nsfw'])){
		$nsfw = '0';
	}else{
		$nsfw = '1';
	}
	
	$fileName = $imgFile["name"];
	$fileType = $imgFile["type"];
	$fileTmpName = $imgFile["tmp_name"];
	$fileError = $imgFile["error"];
	$fileSize = $imgFile["size"];

	$fileExt = explode(".", $fileName);
	$fileActualExt = strtolower(end($fileExt));

	$allowed = array("jpg", "jpeg", "png", "gif");

	if(in_array($fileActualExt, $allowed)){
		if($fileError === 0){
			if($fileSize < 5000000){
				$imgNewName = uniqid("", true).".".$fileActualExt;
				$fileDestination =  'database/uploads/'.$imgNewName;

				if(empty($imgTitle) || empty($imgDesc)){
					$message = "Empty Title or Desc";
					return 0;
				}else{
					/*
					$mature = '0';
					if($nsfw == '1'){
						$mature = '1';
					}
					*/
					$sql = "INSERT INTO images (img_title, img_desc, img_name, user_uid, img_mature, img_tags) VALUES (?, ?, ?, ?, ?, ?)";
					$stmt = mysqli_stmt_init($conn);
					if(!mysqli_stmt_prepare($stmt, $sql)){
						$message =  "SQL Error!";
						return 0;
					}else{
						mysqli_stmt_bind_param($stmt, "ssssss", $imgTitle, $imgDesc, $imgNewName, $User, $nsfw, $imgTag);
						mysqli_stmt_execute($stmt);
						move_uploaded_file($fileTmpName, $fileDestination);
						$message="upload success!";
						header("location: ".$link."/profile/".$User);
						return 0;
					}
				}
			}else{
				$message= "File size is too large!";
				return 0;
			}
		}else{
		 $message="Theres was an error uploading your file!";
		return 0;
		}
	}else{
		$message= "File type not supported! Allowed: jped, jpg, png, gif";
		return 0;
	}
}

?>
