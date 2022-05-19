<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data = json_decode(file_get_contents("php://input"), true);

include 'connect.php';
include 'fxs.php';

if (isset($data["supplierId"])) {
	$supplierId = mysqli_real_escape_string($conn,$data['supplierId']);
	if (validateUser($email,$userId)) {
		$Arr = array();
		$qx = mysqli_query($conn, "select * from product where supplier_id='$userId' and is_hidden='0' order by prod_id desc");
		if(mysqli_num_rows($qx) > 0){
			while ($row = mysqli_fetch_assoc($qx)) {
				$obj = new StdClass();
				$obj->id = $row['prod_id'];
				$obj->name = $row['product_name'];
				$obj->quantity = $row['product_qty'];
				$obj->price = $row['product_price'];
				$obj->description = $row['description'];
				$obj->images = getProductImages($row['prod_id']);
				$obj->categoryId = $row['category_id'];
				$obj->subCategoryId = is_null($row['sub_category_id'])?'':$row['sub_category_id'];
				$obj->date = $row['recorded_date'];
				array_push($Arr, $obj);
			}
		}
		$obj = new StdClass();
		$obj->msg= "supplier products";
        $obj->type= "success";
        $obj->products=$Arr;
        echo json_encode($obj);
	}else{
		$obj = new StdClass();
		$obj->msg= "Please select supplier";
        $obj->type= "error";
        echo json_encode($obj);
	}
}
?>