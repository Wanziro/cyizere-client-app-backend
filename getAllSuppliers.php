<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';
include 'fxs.php';

$Arr = array();
$qx = mysqli_query($conn, "select * from supplier order by company_name asc");
	if(mysqli_num_rows($qx) > 0){
		while ($row = mysqli_fetch_assoc($qx)) {
			$obj = new StdClass();
			$obj->id = $row['supplier_id'];
			$obj->ownerName = $row['supplier_name'];
			$obj->companyName = $row['company_name'];
			$obj->contact = $row['supplier_contact'];
			$obj->email = $row['supplier_email'];
			$obj->address =  $row['supplier_address'];
			$obj->open = $row['start_from'];
			$obj->close = $row['end_time'];
			array_push($Arr, $obj);
		}
	}
$obj = new StdClass();
$obj->msg= "all suppliers";
$obj->type= "success";
$obj->suppliers=$Arr;
echo json_encode($obj);
?>