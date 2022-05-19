<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';

if (isset($data["emailOrPhone"])) {
	$emailOrPhone = mysqli_real_escape_string($conn,$data['emailOrPhone']);
	$password = mysqli_real_escape_string($conn,$data['password']);
	$q = mysqli_query($conn, "select * from clients where client_email='$emailOrPhone' or client_phone='$emailOrPhone' and password='".md5($password)."' and is_active='1'");
	if(mysqli_num_rows($q) == 1){
		while ($row = mysqli_fetch_assoc($q)) {
			//user object
			$userObj = new StdClass();
			$userObj->id = $row['client_id'];
			$userObj->name = is_null($row['client_name'])?'':$row['client_name'];
			$userObj->phone = is_null($row['client_phone'])?'':$row['client_phone'];
			$userObj->email = is_null($row['client_email'])?'':$row['client_email'];
			$userObj->address = is_null($row['client_address'])?'':$row['client_address'];
			//user object

			$obj = new StdClass();
			$obj->msg = "Logged in successful";
			$obj->user = $userObj;
			$obj->type = "success";
			echo json_encode($obj);
		}	
	}else{
		$obj = new StdClass();
		$obj->msg= "Invalid username or password.";
        $obj->type= "error";
        echo json_encode($obj);
	}
}

?>