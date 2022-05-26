<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';

function validateEmail($email){
	include 'connect.php';
	$q = mysqli_query($conn, "select * from clients where client_email='$email'");
	if(mysqli_num_rows($q) > 0){
		return true;
	}else{
		return false;
	}

}

function validatePhone($phone){
	include 'connect.php';
	$q = mysqli_query($conn, "select * from clients where client_phone='$phone'");
	if(mysqli_num_rows($q) > 0){
		return true;
	}else{
		return false;
	}

}

if (isset($data["email"])) {
	$name = mysqli_real_escape_string($conn,$data['name']);
	$phone = mysqli_real_escape_string($conn,$data['phone']);
	$email = mysqli_real_escape_string($conn,$data['email']);
	$password = mysqli_real_escape_string($conn,$data['password']);
	if(validateEmail($email)){
		$obj = new StdClass();
		$obj->msg= "Email already exists.";
        $obj->type= "error";
        echo json_encode($obj);
	}else if(validatePhone($phone)){
		$obj = new StdClass();
		$obj->msg= "Phone number already exists.";
        $obj->type= "error";
	    echo json_encode($obj);
	}else{
		$q = mysqli_query($conn, "insert into clients(client_name,client_phone,client_email,client_address,password,is_active) values('$name','$phone','$email','-','".md5($password)."','1')");
		if($q){
			$get = mysqli_query($conn,"SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA='".$DB_NAME."' AND TABLE_NAME='clients'");
			while($row_id = mysqli_fetch_assoc($get)){
				$id = $row_id['AUTO_INCREMENT'];
			}
			//user object
			$userObj = new StdClass();
			$userObj->id = $id;
			$userObj->name = $name;
			$userObj->phone = $phone;
			$userObj->email = $email;
			//user object

			$obj = new StdClass();
			$obj->msg = "Registered successful";
			$obj->user = $userObj;
			$obj->type = "success";
			echo json_encode($obj);
		}else{
			$obj = new StdClass();
			$obj->msg= "Invalid username or password.";
	        $obj->type= "error";
	        echo json_encode($obj);
		}
	}
}

?>