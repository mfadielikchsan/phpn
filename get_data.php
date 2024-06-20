<?php
require_once 'conn_database.php';

function getKampus() {
    $conn = connectDatabase();
    $sql = "SELECT id, nama_kampus FROM kampus";
    $result = $conn->query($sql);

    $kampus = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $kampus[] = $row;
        }
    }

    $conn->close();
    return $kampus;
}

?>
