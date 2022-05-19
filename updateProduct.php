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
	$name = mysqli_real_escape_string($conn,$data['productName']);
	$categoryId = mysqli_real_escape_string($conn,$data['categoryId']);
	$subCategoryId = mysqli_real_escape_string($conn,$data['subCategoryId']);
	$quantity = mysqli_real_escape_string($conn,$data['quantity']);
	$price = mysqli_real_escape_string($conn,$data['price']);
	$description = mysqli_real_escape_string($conn,$data['description']);
	$productId = mysqli_real_escape_string($conn,$data['productId']);
	
	if (validateUser($email,$userId)) {
		$date = date("d-m-Y H:i");
		if(trim($name) != ''){			
			if(trim($subCategoryId) != ''){
				$q = mysqli_query($conn, "update product set product_name='$name',product_qty='$quantity',product_price='$price',description='$description',category_id='$categoryId',sub_category_id='$subCategoryId' where prod_id='$productId'");
			}else{
				$q = mysqli_query($conn, "update product set product_name='$name',product_qty='$quantity',product_price='$price',product_price='$price',description='$description',category_id='$categoryId' where prod_id='$productId'");
			}
			if($q){
				$obj = new StdClass();
				$obj->msg = "Your product has been updated successful.";
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
			$obj->msg= "Sever rejected to update the product because it is empty";
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