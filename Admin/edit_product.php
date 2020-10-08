<?php
include '../ket_noi.php';
// Get the product data
$code1 = filter_input(INPUT_POST, 'code1');
$name1 = filter_input(INPUT_POST, 'name1');
$price1 = filter_input(INPUT_POST, 'price1', FILTER_VALIDATE_FLOAT);
$product_id1 = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$query = "UPDATE products set productCode=':code', productName=':name',listPrice=':price' WHERE productID=':product_id'";
$statement = $db->prepare($query);
$statement->bindValue(':product_id', $product_id1);
$statement->bindValue(':code', $code1);
$statement->bindValue(':name', $name1);
$statement->bindValue(':price', $price1);
$statement->execute();
    // Display the Product List page
    include('index.php');
?>
