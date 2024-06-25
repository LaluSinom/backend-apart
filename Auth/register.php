<?php
include('../db.php');

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = 'user'; 

if (!empty($name) && !empty($email) && !empty($password)) {
    $sqlCheck = "SELECT COUNT(*) FROM users WHERE email='$email'";
    $queryCheck = mysqli_query($conn, $sqlCheck);
    $hasilCheck = mysqli_fetch_array($queryCheck);

    if ($hasilCheck[0] == 0) {
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            $response = array(
                'status' => 200,
                'message' => 'Data Berhasil Disimpan'
            );
        } else {
            $response = array(
                'status' => 400,
                'message' => 'Data Gagal Disimpan: ' . mysqli_error($conn)
            );
        }
    } else {
        $response = array(
            'status' => 400,
            'message' => 'Data Sudah Ada'
        );
    }
} else {
    $response = array(
        'status' => 400,
        'message' => 'Semua kolom harus diisi'
    );
}

// Mengatur header untuk respons JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
