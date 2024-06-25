<?php
include 'db.php';

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate and sanitize input data
$title = $conn->real_escape_string($data['title'] ?? '');
$location = $conn->real_escape_string($data['location'] ?? '');
$description = $conn->real_escape_string($data['description'] ?? '');
$price = floatval($data['price'] ?? 0);
$rating = floatval($data['rating'] ?? 0);
$owner_name = $conn->real_escape_string($data['owner_name'] ?? '');
$owner_image = $conn->real_escape_string($data['owner_image'] ?? '');
$facilities = $data['facilities'] ?? [];

// Insert owner data
$sql = "INSERT INTO owners (name, image) VALUES ('$owner_name', '$owner_image')";
$conn->query($sql);

$owner_id = $conn->insert_id;

// Insert apartment data without image path
$sql = "INSERT INTO apartments (title, location, description, price, rating, owner_id)
        VALUES ('$title', '$location', '$description', '$price', '$rating', '$owner_id')";
$conn->query($sql);

$apartment_id = $conn->insert_id;

// Insert apartment facilities
foreach ($facilities as $facility) {
    $facility = $conn->real_escape_string($facility);
    
    $sql = "INSERT INTO facilities (name) VALUES ('$facility')";
    $conn->query($sql);

    $facility_id = $conn->insert_id;

    $sql = "INSERT INTO apartment_facilities (apartment_id, facility_id) VALUES ('$apartment_id', '$facility_id')";
    $conn->query($sql);
}

echo json_encode(["message" => "New apartment created successfully"]);

$conn->close();
?>
