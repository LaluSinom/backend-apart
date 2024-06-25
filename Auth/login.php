<?php
include('../db.php');

$name = $_POST['name'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE name = '$name' AND password = '$password'";
$query = mysqli_query($conn, $sql);

if (mysqli_num_rows($query) > 0) {
    $user = mysqli_fetch_assoc($query);

    $data['status'] = 200;
    $data['result'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role']
    ];
} else {
    $data['status'] = 400;
    $data['result'] = "Invalid credentials";
}
header('Content-Type: application/json');
echo json_encode($data);
?>
