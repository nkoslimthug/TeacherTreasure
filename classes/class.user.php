<?php
include('class.password.php');
class User extends Password{
    private $db;
	function __construct($db){
		parent::__construct();
		$this->_db = $db;
	}
	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}
	}
	private function get_user_hash($username){
		try {
			$stmt = $this->_db->prepare('SELECT username, member_type, password FROM tblMembers WHERE username = :username');
			$stmt->execute(array(':username' => $username));
			return $stmt->fetch();
		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}
	public function login($username,$password){
		$user = $this->get_user_hash($username);
		if($this->password_verify($password,$user['password']) == 1){
		    $_SESSION['loggedin'] = true;
		    $_SESSION['username'] = $user['username'];
		    //$_SESSION['memberID'] = $user['memberID'];
		    $_SESSION['member_type'] = $user['member_type'];
//			$_SESSION['userpass'] = $password;
		return true;
		}
	}
	public function logout(){
		session_destroy();
	}
}
?>
