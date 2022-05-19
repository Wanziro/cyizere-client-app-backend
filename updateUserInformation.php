<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';
include 'fxs.php';

if (isset($data["email"])) {
	$email = mysqli_real_escape_string($conn,$data['email']);
	$userId = mysqli_real_escape_string($conn,$data['userId']);
	$openingHours = mysqli_real_escape_string($conn,$data['openingHours']);
	$closingHours = mysqli_real_escape_string($conn,$data['closingHours']);
	$ownerName = mysqli_real_escape_string($conn,$data['ownerName']);
	$address = mysqli_real_escape_string($conn,$data['address']);
	$companyName = mysqli_real_escape_string($conn,$data['companyName']);
	$phone = mysqli_real_escape_string($conn,$data['phone']);
	
	if (validateUser($email,$userId)) {
		if(trim($companyName) != ''){				
			$q = mysqli_query($conn, "update supplier set supplier_name='$ownerName',company_name='$companyName',supplier_contact='$phone',supplier_address='$address',start_from='$openingHours',end_time='$closingHours' where supplier_id='$userId' and supplier_email='$email'");
			if($q){
				$obj = new StdClass();
				$obj->msg = "Merchant's information has been updated successful.";
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