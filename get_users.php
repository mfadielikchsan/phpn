<?php
require_once 'conn_database.php';

$conn = connectDatabase();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user.*, kampus.nama_kampus FROM user join kampus on user.id_kampus = kampus.id";
$result = $conn->query($sql);

$users = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $data = json_encode($row);
        $row['btn'] = "<button class='btn btn-primary' onclick='edit($data)'><i class='fas fa-edit'></i></button>";
        $row['ketaktif'] = $row['active'] == '1' ? 'Yes' : 'No';
        $users[] = $row;
    }
}

$conn->close();

echo json_encode($users);
?>
