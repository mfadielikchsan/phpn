<?php
require_once 'get_data.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $primarykey = $_POST['primarykey'];
    $nama = $_POST['nama'];
    $hobi = $_POST['hobi'];
    $id_kampus = $_POST['kampus'];
    $active = $_POST['active'];

    $conn = connectDatabase();

    if ($primarykey == '') {
        // Insert data
        $sql = "INSERT INTO user (nama, hobi, id_kampus, active) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $nama, $hobi, $id_kampus, $active);
        $stmt->execute();

        $response = array(
            'head' => 'Success',
            'msg' => 'Data berhasil ditambahkan.',
            'status' => 'success'
        );

        $stmt->close();
    } else {
        // Update data
        $sql = "UPDATE user SET nama = ?, hobi = ?, id_kampus = ?, active = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiii", $nama, $hobi, $id_kampus, $active, $primarykey);
        $stmt->execute();

        $response = array(
            'head' => 'Success',
            'msg' => 'Data berhasil diperbarui.',
            'status' => 'success'
        );

        $stmt->close();
    }

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    header('Content-Type: application/json');
    echo json_encode(array(
        'head' => 'Error',
        'msg' => 'Metode tidak diizinkan.',
        'status' => 'error'
    ));
}
?>
