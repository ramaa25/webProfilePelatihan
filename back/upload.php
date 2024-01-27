<?php
//upload.php
$image = '';
if (isset($_FILES['file']['name'])) {
    $image_name = $_FILES['file']['name'];
    $valid_extensions = array("jpg", "jpeg", "png");
    $extension = pathinfo($image_name, PATHINFO_EXTENSION);
    if (in_array($extension, $valid_extensions)) {
        $rename = time() . '.' . $extension;
        $upload_path = 'uploads/' . $rename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)) {
            // Insert image path to database
            $conn = new mysqli(
                "localhost",
                "root",
                '',
                "latihan"
            );
            $sql = "INSERT INTO gambar (keterangan, filename) VALUES ('', '$rename')";
            $conn->query($sql);
            $conn->close();
            $message = 'Image Uploaded';
            $image = $upload_path;
        } else {
            $message = 'There is an error while uploading image';
        }
    } else {
        $message = 'Only .jpg, .jpeg and .png Image allowed to upload';
    }
} else {
    $message = 'Select Image';
}
$output = array(
    'message' => $message,
    'image' => $image
);
echo json_encode($output);
