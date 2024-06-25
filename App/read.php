<?php
include("../db.php");

$sql = "SELECT a.*, o.name as owner_name, o.image as owner_image 
        FROM apartments a 
        JOIN owners o ON a.owner_id = o.id";
$result = $conn->query($sql);

$apartments = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $apartment_id = $row['id'];
        
        // Get facilities for each apartment
        $sql_facilities = "SELECT f.name FROM facilities f 
                           JOIN apartment_facilities af ON f.id = af.facility_id 
                           WHERE af.apartment_id = $apartment_id";
        $result_facilities = $conn->query($sql_facilities);
        $facilities = array();
        if ($result_facilities->num_rows > 0) {
            while($facility = $result_facilities->fetch_assoc()) {
                array_push($facilities, $facility['name']);
            }
        }
        
        $row['facilities'] = $facilities;
        array_push($apartments, $row);
    }
}

echo json_encode($apartments);

$conn->close();
?>
