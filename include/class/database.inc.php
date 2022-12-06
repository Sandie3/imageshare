<?php
	/* sqllink.inc.php
	 * A wrapper class for the MySQL connection object
	 * also includes some useful helper functions (check if logged in) */

	class SQLLink{
		// SQL connection object
		var $conn;

		// SQL login details
		var $sql_user, $sql_pass, $sql_db, $sql_server;

		// Initialize database
		function __construct($user, $pass){
			// SQL servername
			$this->sql_server = "localhost";

			// SQL login information
			$this->sql_user = $user;
			$this->sql_pass = $pass;

			// SQL database name
			$this->sql_db = "hiraeth";
			
			// Connect
			$this->conn = mysqli_connect($this->sql_server, $this->sql_user, $this->sql_pass, $this->sql_db);

			// Check connection
			if ($this->conn === false){
				exit("Connection failed: " . mysqli_connect_error());
			}

			$this->init_session();
		}

		// Auto-close database on exit
		function __destruct(){
			mysqli_close($this->conn);
			unset($this->conn);
		}

		// Start / resume session
		function init_session(){
			if (session_status() != PHP_SESSION_NONE){
				return;
			}
			
			session_start();
		}

		function is_logged_in(){
			return isset($_SESSION["userUid"]);
		}
	}
?>
