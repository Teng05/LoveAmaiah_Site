<?php
session_start();


if (!isset($_SESSION['OwnerID'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

require_once('../classes/database.php'); 
$con = new database();


header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    
   
    $productID = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);

   
    if ($productID) {
   
        if ($con->restoreProduct($productID)) {
            echo json_encode(['success' => true, 'message' => 'Product restored successfully.']);
        } else {
       
            echo json_encode(['success' => false, 'message' => 'Failed to restore product.']);
        }
    } else {
        
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid Product ID provided.']);
    }
} else {
   
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>