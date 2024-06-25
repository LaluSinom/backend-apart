<?php
include 'db.php';

// Periksa apakah parameter 'id' ada dalam permintaan
if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];

    $data = json_decode(file_get_contents("php://input"), true);

    // Pastikan data yang diperlukan tersedia dalam tubuh permintaan
    if(isset($data['title'], $data['location'], $data['description'], $data['price'], $data['rating'], $data['image'], $data['owner_name'], $data['owner_image'], $data['facilities'])) {

        $title = $data['title'];
        $location = $data['location'];
        $description = $data['description'];
        $price = $data['price'];
        $rating = $data['rating'];
        $image = $data['image'];
        $owner_name = $data['owner_name'];
        $owner_image = $data['owner_image'];
        $facilities = $data['facilities'];

        // Update owner data
        $sql = "UPDATE owners o 
                JOIN apartments a ON a.owner_id = o.id 
                SET o.name='$owner_name', o.image='$owner_image' 
                WHERE a.id=$id";
        $conn->query($sql);

        // Update apartment data
        $sql = "UPDATE apartments 
                SET title='$title', location='$location', description='$description', price='$price', rating='$rating', image='$image' 
                WHERE id=$id";
        $conn->query($sql);

        // Update apartment facilities
        $sql = "DELETE FROM apartment_facilities WHERE apartment_id=$id";
        $conn->query($sql);

        foreach ($facilities as $facility) {
            // Check if facility already exists
            $sql = "SELECT id FROM facilities WHERE name='$facility'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $facility_id = $result->fetch_assoc()['id'];
            } else {
                // Insert new facility
                $sql = "INSERT INTO facilities (name) VALUES ('$facility')";
                $conn->query($sql);
                $facility_id = $conn->insert_id;
            }
            // Map apartment to facility
            $sql = "INSERT INTO apartment_facilities (apartment_id, facility_id) VALUES ('$id', '$facility_id')";
            $conn->query($sql);
        }

        echo json_encode(["message" => "Apartment updated successfully"]);
    } else {
        // Jika data yang diperlukan tidak tersedia
        echo json_encode(["error" => "Missing required data fields"]);
    }

} else {
    // Jika parameter 'id' tidak ada dalam permintaan
    echo json_encode(["error" => "Missing 'id' parameter"]);
}

$conn->close();
?>
