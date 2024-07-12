<?php
$username = $name = $password = $email = $role =$status = $category_name= $description= $supplier_name = $phone =$product_name =$price=$cost =$stock_quantity=$expiration_date =$barcode =$customer_name  =$discount_name =$discount_percent=$start_date =$end_date=$start_time=$end_time="";

$usernameErr = $nameErr = $passwordErr = $emailErr = $roleErr =$statusErr = $category_nameErr= $descriptionErr= $supplier_nameErr = $phoneErr =$product_nameErr =$priceErr=$costErr =$stock_quantityErr=$expiration_dateErr =$barcodeErr =$customer_nameErr  =$discount_nameErr =$discount_percentErr=$start_dateErr =$end_dateErr=$start_timeErr=$end_timeErr="";
if ($_SERVER["REQUEST_METHOD"]== "POST"){
    if(empty($_POST("Username"))){
        $usernameErr="Username is required";
    }
    else {
        $username= test_input($_POST("Username"));
    }

    if(empty($_POST("name"))){
        $nameErr="Name is required";
    }
    else {
        $name= test_input($_POST("full_name"));
    }

    if(empty($_POST("password"))){
        $passwordErr="Password is required";
    }
    else {
        $password= test_input($_POST("Password"));
    }
    
    if(empty($_POST("email"))){
        $emailErr="Email is required";
    }
    else {
        $email= test_input($_POST("Email"));
    }
    
    if(empty($_POST("role"))){
        $roleErr="Role is required";
    }
    else {
        $role= test_input($_POST("role"));
    }

    if(empty($_POST("status"))){
        $statusErr="Status is required";
    }
    else {
        $status= test_input($_POST("status"));
    }

    if(empty($_POST("description"))){
        $descriptionErr="Description is required";
    }
    else {
        $description= test_input($_POST("description"));
    }

    if(empty($_POST("supplier_name"))){
        $supplier_nameErr="Suppliers name is required";
    }
    else {
        $supplier_name= test_input($_POST("supplier_name"));
    }

    if(empty($_POST("phone"))){
        $phoneErr="Phone number is required";
    }
    else {
        $phone= test_input($_POST("phone"));
    }

    if(empty($_POST("product_name"))){
        $product_nameErr="Product name is required";
    }
    else {
        $product_name= test_input($_POST("product_name"));
    }
    
    if(empty($_POST("price"))){
        $priceErr="Price is required";
    }
    else {
        $price= test_input($_POST("price"));
    }
    

}  



    $cost= test_input($_POST("cost"));
    $stock_quantity= test_input($_POST("stock_quantity"));
    $expiration_date= test_input($_POST("expiration_date"));
    $barcode= test_input($_POST("barcode"));
    $customer_name= test_input($_POST("customer_name"));
    $discount_percent= test_input($_POST("discount_percent"));
    $start_date= test_input($_POST("start_date"));
    $end_date= test_input($_POST("end_date"));
    $start_time= test_input($_POST("start_time"));
    $end_time= test_input($_POST("end_time"));
}

function test_input($data){
    $data=trim($data);
    $data=stripslashes($data);
    $data=htmlspecialchars($data);
    return $data;
}
?>