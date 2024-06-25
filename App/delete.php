<?php
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = $conn->real_escape_string($data['id']);

    if (!empty($id)) {
        // Hapus apartment facilities
        $sql = "DELETE FROM apartment_facilities WHERE apartment_id=$id";
        if ($conn->query($sql) !== TRUE) {
            echo json_encode(["error" => "Failed to delete apartment facilities: " . $conn->error]);
            $conn->close();
            exit();
        }

        // Hapus apartment
        $sql = "DELETE FROM apartment WHERE id=$id";
        if ($conn->query($sql) !== TRUE) {
            echo json_encode(["error" => "Failed to delete apartment: " . $conn->error]);
            $conn->close();
            exit();
        }

        echo json_encode(["message" => "Apartment deleted successfully"]);
    } else {
        echo json_encode(["error" => "Empty 'id' parameter"]);
    }
} else {
    echo json_encode(["error" => "Missing 'id' parameter"]);
}

$conn->close();
?>
