<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';
include 'fxs.php';

function validatePhone($phone,$email){
	include 'connect.php';
	$q = mysqli_query($conn, "select * from clients where client_phone='$phone' and client_email!='$email'");
	if(mysqli_num_rows($q) > 0){
		return true;
	}else{
		return false;
	}

}

if (isset($data["email"])) {
	$email = mysqli_real_escape_string($conn,$data['email']);
	$userId = mysqli_real_escape_string($conn,$data['userId']);
	$names = mysqli_real_escape_string($conn,$data['names']);
	$address = mysqli_real_escape_string($conn,$data['address']);
	$phone = mysqli_real_escape_string($conn,$data['phone']);
	
	if(validatePhone($phone,$email)){
		$obj = new StdClass();
		$obj->msg= "Phone number already exists";
        $obj->type= "error";
        echo json_encode($obj);
	}else if (validateUser($email,$userId)) {
		if(trim($phone) != ''){				
			$q = mysqli_query($conn, "update clients set client_name='$names',client_phone='$phone',client_address='$address',client_phone='$phone' where client_id='$userId' and client_email='$email'");
			if($q){
				$obj = new StdClass();
				$obj->msg = "Your information has been updated successful.";
				$obj->type = "success";
				echo json_encode($obj);
			}else{
				$obj = new StdClass();
				$obj->msg = "Something went wrong. Try again later after some time.";
				$obj->type = "error";
				echo json_encode($obj);
			}
		}else{
			$obj = new StdClass();
			$obj->msg= "Sever rejected to update empty user info";
	        $obj->type= "error";
	        echo json_encode($obj);
		}
	}else{
		$obj = new StdClass();
		$obj->msg= "Invalid credentials. server cant handle this request";
        $obj->type= "error";
        echo json_encode($obj);
	}
}

?>