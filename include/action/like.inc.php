<?php
        $php_root = $_SERVER['DOCUMENT_ROOT'];
        require ($php_root.'/include/conf.php');

        // Follow or unfollow button clicked
        if (isset($_POST['like']) || isset($_POST['unlike'])){
                $pid = $_POST['post_id'];
                $sid = $_SESSION['userUid'];

                // Check if logged in
                if(!isset($sid) || empty($sid)){
                        $message = "Error, not logged in!";
                        header("Location: /login");
                        return 1;
                }

                // Check if RID is valid
                if(!isset($pid) || empty($pid)){
                        $message = "Error getting post ID";
                        header("Location: /home");
                        return 1;
                }

                // Execute SQL follow
                if(isset($_POST['like'])){
                        $message = $sid." -> ".$pid;
                        mysqli_query($conn, "INSERT INTO likes (user_uid, post_id, liked) VALUES ('".$sid."', '".$pid."', 'true')");
                }

                // Execute SQL unfollow
                if(isset($_POST['unlike'])){
                        $message = $sid." X ".$pid;
                        mysqli_query($conn, "DELETE FROM likes WHERE user_uid='".$sid."' AND post_id='".$pid."'");
                }
        }
?>

