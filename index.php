<?php 
require_once 'includes/header.php'; 
require_once 'config/database.php';

$sql = "SELECT * FROM notices ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="main-panel" style="overflow-y: auto; height: 100vh;">
  <div class="content-wrapper" style="padding-bottom: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="page-title">TechNexus Notice Board</h3>

<!--shows this option inly to admin-->
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
      <a href="add_notice.php" class="btn btn-success" style="background-color: #00d25b; border: none;">+ Post New Notice</a>
      <?php endif; ?>
    </div>

    <!-- AJAX Search Input Box -->
    <div class="row mb-4">
      <div class="col-12">
        <input type="text" id="searchInput" class="form-control text-white" placeholder="🔍 Type to search notices live..." style="background-color: #2A3038; border: 1px solid #2c2e33; padding: 12px;">
      </div>
    </div>

    <!-- Notice Cards Container -->
    <div class="row" id="noticeContainer">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($notice = mysqli_fetch_assoc($result)): ?>
          <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h4 class="card-title text-primary" style="color: #00d25b !important;"><?php echo htmlspecialchars($notice['title']); ?></h4>
                  <small class="text-muted"><?php echo date('M d, Y', strtotime($notice['created_at'])); ?></small>
                </div>

                <!-- Display Uploaded Event Poster -->
                <?php if (!empty($notice['image_path']) && file_exists($notice['image_path'])): ?>
                  <div class="my-3 text-center">
                    <img src="<?php echo htmlspecialchars($notice['image_path']); ?>" alt="Notice Poster" class="img-fluid rounded" style="max-height: 250px; width: 100%; object-fit: cover; border: 1px solid #2c2e33;">
                  </div>
                <?php endif; ?>
<!-- converts \n into <br>-->
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
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12">
          <div class="alert alert-secondary text-center" style="background-color: #191c24; color: #6c757d; border: 1px solid #2A3038;">
            No notices published yet. Click "+ Post New Notice" to create one.
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>

<!-- AJAX part-->
<script>
document.getElementById('searchInput').addEventListener('input', function() {
    let searchQuery = this.value;

    fetch('search_notices.php?q=' + encodeURIComponent(searchQuery))
        .then(response => response.text())
        .then(data => {
            document.getElementById('noticeContainer').innerHTML = data;
        })
        .catch(error => console.error('AJAX Error:', error));
});
</script>

<?php require_once 'includes/footer.php'; ?>