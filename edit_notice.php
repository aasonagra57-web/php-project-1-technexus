<?php
session_start();
require_once 'config/database.php';


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

// Checking id given by user
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch existing notice data from database
$sql = "SELECT * FROM notices WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) !== 1) {
    header("Location: index.php");
    exit();
}

$notice = mysqli_fetch_assoc($result);

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image_path = $notice['image_path']; // Keep existing image by default

    // Handle File Upload if a new image is selected
    if (isset($_FILES['notice_image']) && $_FILES['notice_image']['error'] === 0) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = $_FILES['notice_image']['name'];
        $target_filepath = $upload_dir . $file_name;

        if (move_uploaded_file($_FILES['notice_image']['tmp_name'], $target_filepath)) {
            // Delete old image if it exists to save space
            if (!empty($notice['image_path']) && file_exists($notice['image_path'])) {
                unlink($notice['image_path']);
            }
            $image_path = $target_filepath;
        }
    }

    if (!empty($title) && !empty($content)) {
        $update_sql = "UPDATE notices SET title = '$title', content = '$content', image_path = " . ($image_path ? "'$image_path'" : "NULL") . " WHERE id = $id";
        $update_result = mysqli_query($conn, $update_sql);

        if ($update_result) {
            $message = "Notice updated successfully!";
            $message_type = "success";
            
            // Refresh local notice array with updated values
            $notice['title'] = $title;
            $notice['content'] = $content;
            $notice['image_path'] = $image_path;
        } else {
            $message = "Error updating notice: " . mysqli_error($conn);
            $message_type = "danger";
        }
    } else {
        $message = "Please fill in all required fields.";
        $message_type = "warning";
    }
}

require_once 'includes/header.php';    
?>

<div class="main-panel" style="overflow-y: auto; height: 100vh;">
    <div class="content-wrapper" style="padding-bottom: 100vh;">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Club Notice</h4>
                        
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $message_type; ?>" style="background-color: <?php echo $message_type === 'success' ? '#00d25b' : '#fc424a'; ?>; color: white; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <form class="forms-sample" action="edit_notice.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title">Notice Title</label>
                                <input type="text" name="title" class="form-control text-white" id="title" value="<?php echo htmlspecialchars($notice['title']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="content">Notice Content</label>
                                <textarea name="content" class="form-control text-white" id="content" rows="6" required><?php echo htmlspecialchars($notice['content']); ?></textarea>
                            </div>

                            <?php if (!empty($notice['image_path']) && file_exists($notice['image_path'])): ?>
                                <div class="form-group">
                                    <label>Current Poster Preview:</label>
                                    <div class="mb-2">
                                        <img src="<?php echo htmlspecialchars($notice['image_path']); ?>" alt="Current Poster" class="img-fluid rounded" style="max-height: 150px; border: 1px solid #2c2e33;">
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="form-group">
                                <label>Change Event Poster (Optional)</label>
                                <input type="file" name="notice_image" class="form-control" accept="image/*" style="background-color: #2A3038; border: 1px solid #2c2e33;">
                            </div>
                            <button type="submit" class="btn btn-primary mr-2" style="background-color: #00d25b; border: none;">Update Notice</button>
                            <a href="index.php" class="btn btn-dark">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once 'includes/footer.php'; ?>