<?php
session_start();
require_once 'config/database.php';

$search = mysqli_real_escape_string($conn, $_GET['q'] ?? '');

$sql = "SELECT * FROM notices WHERE title LIKE '%$search%' OR content LIKE '%$search%' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($notice = mysqli_fetch_assoc($result)) {
?>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="card-title text-primary" style="color: #00d25b !important;"><?php echo htmlspecialchars($notice['title']); ?></h4>
                        <small class="text-muted"><?php echo date('M d, Y', strtotime($notice['created_at'])); ?></small>
                    </div>

                    <?php if (!empty($notice['image_path']) && file_exists($notice['image_path'])): ?>
                        <div class="my-3 text-center">
                            <img src="<?php echo htmlspecialchars($notice['image_path']); ?>" alt="Notice Poster" class="img-fluid rounded" style="max-height: 250px; width: 100%; object-fit: cover; border: 1px solid #2c2e33;">
                        </div>
                    <?php endif; ?>

                    <p class="card-text"><?php echo nl2br(htmlspecialchars($notice['content'])); ?></p>
                    
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <div class="mt-3">
                        <a href="edit_notice.php?id=<?php echo $notice['id']; ?>" class="btn btn-warning btn-sm mr-2" style="background-color: #ffab00; border: none; color: #000; font-weight: bold;">Edit</a>
                        <a href="delete_notice.php?id=<?php echo $notice['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this notice?');" style="background-color: #fc424a; border: none;">Delete</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<div class="col-12"><div class="alert alert-secondary text-center" style="background-color: #191c24; color: #6c757d; border: 1px solid #2A3038;">No notices found matching your search.</div></div>';
}
?>