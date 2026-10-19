<?php
session_start();
require_once 'config/database.php';

// only Admin 
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// it escapes special charactes in the user input 
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

// Handle File Upload
    $image_path = NULL;
    if (isset($_FILES['notice_image']) && $_FILES['notice_image']['error'] === 0) {
        $upload_dir = 'uploads/';

// Automatically creates uploads folder if it doesn't exist 
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_name = $_FILES['notice_image']['name'];
        $target_filepath = $upload_dir . $file_name;

// Moving uploaded files
        if (move_uploaded_file($_FILES['notice_image']['tmp_name'], $target_filepath)) {
            $image_path = $target_filepath;
        }
    }

    if (!empty($title) && !empty($content)) {
        
        $sql = "INSERT INTO notices (title, content, image_path) VALUES ('$title', '$content', " . ($image_path ? "'$image_path'" : "NULL") . ")";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $message = "Notice published successfully!";
            $message_type = "success";
        } else {
            $message = "Error publishing notice: " . mysqli_error($conn);
            $message_type = "danger";
        }
    } else {
        $message = "Please fill in all required fields.";
        $message_type = "warning";
    }
}

require_once 'includes/header.php';    
?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Post Club Notice</h4>
                        
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $message_type; ?>" style="background-color: <?php echo $message_type === 'success' ? '#00d25b' : '#fc424a'; ?>; color: white; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <form class="forms-sample" action="add_notice.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title">Notice Title</label>
                                <input type="text" name="title" class="form-control text-white" id="title" placeholder="e.g. Annual Hackathon 2026 Registration" required>
                            </div>
                            <div class="form-group">
                                <label for="content">Notice Content</label>
                                <textarea name="content" class="form-control text-white" id="content" rows="6" placeholder="Write full notice details here..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Event Poster (Optional)</label>
                                <input type="file" name="notice_image" class="form-control" accept="image/*" style="background-color: #2A3038; border: 1px solid #2c2e33;">
                            </div>
                            <button type="submit" class="btn btn-primary mr-2" style="background-color: #00d25b; border: none;">Publish Notice</button>
                            <a href="index.php" class="btn btn-dark">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>