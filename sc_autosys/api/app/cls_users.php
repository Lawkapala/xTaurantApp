<?php
	session_start();

	Class User {
		private $db_obj;
		
		function __construct() {
			require_once 'config/dbconn.php';
			
			$this->db_obj = new DBConfig();
		}
		
		function add_user() {
			$msg = array("status" => 0, "msg" => "Error adding user");
			
			$title = $_POST['title'] ;
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$role = $_POST['role'];
			$username = $_POST['uname'];
			$password = $_POST['pass'];
			$branch = $_POST['branch'];
			$status = 'Approved';
			
			$sql = "INSERT INTO sc_users (title, first_name, last_name, role, username, password, branch, status, datecreated)
						VALUES (?, ?, ?, ?, ?, ?, ?, ?, now());";
						
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('ssssssss', $title, $fname, $lname, $role, $username, $password, $branch, $status);
				if($stmt->execute() ){
					$msg = array("status" => 1, "msg" => "Your record inserted successfully");
				}
				else {
					$msg = array("status" => 0, " msg" => "Error: <br>" . $sql ."<br>". mysqli_error($conn)); 
				}
				
				mysqli_close($conn);
			}
			catch(Exception $e){
				
			}		
			
			return $msg;
		}
		
		function get_user($id = 0){
			$users = array("status" => 0, "msg" => "No Users Found");
			
			$sql = "SELECT usrid, title, first_name, last_name, role, username, password, branch, status
						FROM sc_users";
						
			if($id != 0){
				$sql .= " WHERE usrid = ?;";
			}
			
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				
				if($id != 0)
					$stmt->bind_param('d', $id);
				
				$stmt->execute();
				$stmt->bind_result($userid, $title, $fname, $lname, $role, $uname, $pass, $branch, $status);
				
				while($stmt->fetch()){
					$result[] = array("userid" => $userid,
											"title" => $title,
											"fname" => $fname,
											"lname" => $lname,
											"role" => $role,
											"uname" => $uname,
											"pword" => $pass,
											"branch" => $branch,
											"status" => $status);
				}
				
				if(isset($result))
					$users = $result;
				
				mysqli_close($conn);
			}
			catch (Exception $e){
				
			}
			
			return $users;
		}
		
		function update_user($id) {
			$msg = array("status" => 0, "msg" => "Error adding user");
			
			$title = $_POST['title'] ;
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$role = $_POST['role'];
			$username = $_POST['uname'];
			$password = $_POST['pass'];
			$branch = $_POST['branch'];
			$status = 'Approved';
			
			// $title = 'Mr' ;
			// $fname ='Hallelujah';
			// $lname = 'Hapstism';
			// $role = 'Admin';
			// $username = 'arios';
			// $password ='gundam';
			// $branch = 'VI';
			// $status = 'Approved';
			
			$sql = "UPDATE sc_users 
						SET title = ?, 
							   first_name = ?, 
							   last_name = ?, 
							   role = ?, 
							   username = ?, 
							   password = ?,
							   branch = ?
						WHERE usrid = ?;";
						
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('sssssssd', $title, $fname, $lname, $role, $username, $password, $branch, $id);
				if($stmt->execute() ){
					$msg = array("status" => 1, "msg" => "User record successfully updated");
				}
				else {
					$msg = array("status" => 0, " msg" => "Error: <br>" . $sql ."<br>". mysqli_error($conn)); 
				}
				
				mysqli_close($conn);
			}
			catch(Exception $e){
				
			}		
			
			return $msg;
		}
		
		function delete_user($id) {
			$msg = array("status" => 0, "msg" => "Error deleting user");
			
			$sql = "DELETE FROM sc_users 
						WHERE usrid = ?;";
						
			try{
				$conn = $this->db_obj->db_connect();
				$stmt = $conn->prepare($sql);
				$stmt->bind_param('d', $id);
				if($stmt->execute() ){
					$msg = array("status" => 1, "msg" => "User record successfully deleted");
				}
				else {
					$msg = array("status" => 0, " msg" => "Error: <br>" . $sql ."<br>". mysqli_error($conn)); 
				}
				
				mysqli_close($conn);
			}
			catch(Exception $e){
				
			}		
			
			return $msg;
		}
		
		function login_user() {
			$msg = array("status" => 0, "msg" => "Invalid login credentials!");
			
			//$uname = isset($_POST['username'])? mysql_real_escape_string($_POST['username']) : '';
			//$pass = isset($_POST['password'])? mysql_real_escape_string($_POST['password']) : '';
			
			//$uname = isset($_POST['username'])? filter_input(INPUT_POST, $_POST['username']) : '';
			//$pass = isset($_POST['password'])? filter_input(INPUT_POST, $_POST['password']) : '';
			
			$uname = $_POST['username'];
			$pass = $_POST['password'];
			$branch = $_POST['branch'];
			
			$users = $this->get_user();
			//$msg = array("user" => $uname, "pass" => $pass);
			
			//print_r($users);
			//echo '<br>'.$uname;
			//$uname = 'bene';
			
			foreach($users as $user){
				if($uname != '' && strtolower($uname) == strtolower($user['uname']) && $pass == $user['pword']){
					$_SESSION['sc_userid'] = $user['userid'];
					$_SESSION['sc_title'] = $user['title'];
					$_SESSION['sc_fname'] = $user['fname'];
					$_SESSION['sc_lname'] = $user['lname'];
					$_SESSION['sc_role'] = $user['role'];
					$_SESSION['sc_uname'] = $user['uname'];
					$_SESSION['sc_branch'] = $branch;
					$_SESSION['sc_status'] = $user['status'];
					
					$msg = array("status" => 1, "msg" => "Logged in successfully");
					//$msg = $_POST;
					//return $msg;
					break;
				}
				else {
					$msg = array("status" => 0, "msg" => "User or password not found!");
				}
			}
			
			return $msg;
		}
		
		function logout_user() {
			session_unset();
			
			$msg = array("status" => "1", "msg" => "Logged Out Successfully!");
			
			return $msg;
		}
		
		function get_logged_user_detail() {
			$msg = array("status" => 0, "msg" => "Invalid login credentials!");
			
			if(isset($_SESSION['sc_uname'])){					
					$msg  = array("userid" => $_SESSION['sc_userid'],
											"title" => $_SESSION['sc_title'] ,
											"fname" => $_SESSION['sc_fname'],
											"lname" => $_SESSION['sc_lname'],
											"role" => $_SESSION['sc_role'],
											"uname" => $_SESSION['sc_uname'],
											"branch" => $_SESSION['sc_branch'],
											"status" => $_SESSION['sc_status']);
			}
			else {
				$msg = array("status" => 0, "msg" => "No logged in User!");
			}
			
			return $msg;
		}
		
	}
?>