<?php
header('Content-Type: application/json');
require_once 'config/database.php';

$sql = "SELECT id, title, content, image_path, created_at FROM notices ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$notices = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notices[] = $row;
    }
}

// Output data as JSON with pretty print
echo json_encode([
    "status" => "success",
    "count" => count($notices),
    "data" => $notices
], JSON_PRETTY_PRINT);
?>