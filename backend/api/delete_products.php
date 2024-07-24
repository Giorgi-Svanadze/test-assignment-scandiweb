<?php

$allowed_origins = [
    "http://scandiweb-test.great-site.net",
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/Database.php';

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));

if (isset($data->skus)) {
    $skus = $data->skus;
    $inQuery = implode(',', array_fill(0, count($skus), '?'));

    $query = "DELETE FROM products WHERE sku IN ($inQuery)";
    $stmt = $db->prepare($query);

    foreach ($skus as $index => $sku) {
        $stmt->bindValue($index + 1, $sku);
    }

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("message" => "Products were deleted."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to delete products."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Invalid input."));
}

?>
