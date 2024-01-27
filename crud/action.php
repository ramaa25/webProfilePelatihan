<?php
// Koneksi ke database dengan PDO
$connect = new PDO("mysql:host=localhost; dbname=latihan", "root", "");

// Menerima data dari permintaan POST
$received_data = json_decode(file_get_contents("php://input"));

// Membuat variabel data
$data = array();

$action = isset($_POST['action']) ? $_POST['action'] : $received_data->action;

// Menangani permintaan untuk menampilkan semua data
if ($received_data->action == 'fetchall') {
    // Perintah untuk mengambil data dari database
    $query = "SELECT * FROM lat ORDER BY id DESC";
    $statement = $connect->prepare($query);
    $statement->execute();

    // Melakukan perulangan dan memasukkan setiap data ke dalam variabel data
    while ($row = $statement->fetch()) {
        $data[] = $row;
    }

    // Menampilkan data dalam bentuk JSON
    echo json_encode($data);
}

// Menangani permintaan untuk menyimpan data
if ($action == 'insert') {
    $image_name = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $rename = time() . '.' . $extension;
    $upload_path = 'upload/' . $rename;

    // Pindahkan file yang diunggah ke direktori yang ditentukan
    move_uploaded_file($tmp, $upload_path);

    // Membuat array data untuk disimpan ke dalam database
    $data = array(
        'nama' => $_POST['nama'],
        'gambar' => $rename
    );

    // Query untuk menyimpan data ke dalam database
    $query = "INSERT INTO lat (nama, gambar) VALUES (:nama, :gambar)";
    $statement = $connect->prepare($query);
    $statement->execute($data);
}

// Menangani permintaan untuk menghapus data
if ($received_data->action == 'delete') {
    $data = array(
        'id' => $received_data->id
    );
    $query = "DELETE FROM lat WHERE id = :id";
    $statement = $connect->prepare($query);
    $statement->execute($data);
}

// Menangani permintaan untuk memperbarui data
if ($action == 'update') {
    $image_name = $_FILES['file']['name'];
    $tmp = $_FILES['file']['tmp_name'];
    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    $rename = time() . '.' . $extension;
    $upload_path = 'upload/' . $rename;
    if ($image_name != '') {

        // Pindahkan file yang diunggah ke direktori yang ditentukan
        move_uploaded_file($tmp, $upload_path);
        $data = [
            'nama' => $_POST['nama'],
            'id' => $_POST['id'],
            'gambar' => $rename
        ];
        $query = 'UPDATE lat SET nama = :nama, gambar = :gambar WHERE id = :id';
    } else {
        $data = [
            'nama' => $_POST['nama'],
            'id' => $_POST['id'],
        ];
        $query = 'UPDATE lat SET nama = :nama WHERE id = :id';
    }
    $statement = $connect->prepare($query);
    $statement->execute($data);
}
