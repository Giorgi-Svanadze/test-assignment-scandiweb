<?php

$allowed_origins = [
    "http://scandiweb-test.great-site.net",
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/Database.php';
include_once '../classes/Product.php';
include_once '../classes/DVD.php';
include_once '../classes/Book.php';
include_once '../classes/Furniture.php';

$database = new Database();
$db = $database->getConnection();

$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->sku) && !empty($data->name) && !empty($data->price) && !empty($data->type)) {
        $sku = $data->sku;
        $name = $data->name;
        $price = $data->price;
        $type = $data->type;

        $query = "SELECT sku FROM products WHERE sku = ?";
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $sku);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(array("message" => "SKU already exists."));
            exit();
        }

        if ($type === 'DVD') {
            if (!empty($data->size)) {
                $size = $data->size;
                $product = new DVD($sku, $name, $price, $size);
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Please provide the size for DVD."));
                exit();
            }
        } elseif ($type === 'Book') {
            if (!empty($data->weight)) {
                $weight = $data->weight;
                $product = new Book($sku, $name, $price, $weight);
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Please provide the weight for Book."));
                exit();
            }
        } elseif ($type === 'Furniture') {
            if (!empty($data->height) && !empty($data->width) && !empty($data->length)) {
                $height = $data->height;
                $width = $data->width;
                $length = $data->length;
                $product = new Furniture($sku, $name, $price, $height, $width, $length);
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Please provide the dimensions for Furniture."));
                exit();
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid product type."));
            exit();
        }

        $product->save($db);
        http_response_code(201);
        echo json_encode(array("message" => "Product added successfully."));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Please, submit required data."));
    }
} elseif ($request_method === 'GET') {
    $query = "SELECT * FROM products ORDER BY id";
    $stmt = $db->prepare($query);
    $stmt->execute();

    $products_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $product_item = array(
            "id" => $id,
            "sku" => $sku,
            "name" => $name,
            "price" => $price,
            "type" => $type,
            "specificAttribute" => ""
        );

        if ($type === 'DVD') {
            $product_item["specificAttribute"] = "Size: {$size} MB";
        } elseif ($type === 'Book') {
            $product_item["specificAttribute"] = "Weight: {$weight} Kg";
        } elseif ($type === 'Furniture') {
            $product_item["specificAttribute"] = "Dimensions: {$height} x {$width} x {$length}";
        }

        array_push($products_arr, $product_item);
    }

    http_response_code(200);
    echo json_encode($products_arr);
} elseif ($request_method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!empty($data->ids)) {
        $ids = implode(',', array_map('intval', $data->ids));
        
        $query = "DELETE FROM products WHERE id IN ($ids)";
        $stmt = $db->prepare($query);
        
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array("message" => "Products deleted successfully."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete products."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "No product ids provided."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed."));
}
?>
