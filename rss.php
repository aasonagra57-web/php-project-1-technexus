<?php
header("Content-Type: application/rss+xml; charset=UTF-8");
require_once 'config/database.php';

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<rss version="2.0">
    <channel>
        <title>TechNexus Notice Board RSS Feed</title>
        <link>http://localhost/technexus/index.php</link>
        <description>Latest club announcements and updates from TechNexus</description>
        <language>en-us</language>

        <?php
        $sql = "SELECT * FROM notices ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <item>
                    <title><?php echo htmlspecialchars($row['title']); ?></title>
                    <link>http://localhost/technexus/index.php</link>
                    <description><?php echo htmlspecialchars($row['content']); ?></description>
                    <pubDate><?php echo date(DATE_RSS, strtotime($row['created_at'])); ?></pubDate>
                </item>
                <?php
            }
        }
        ?>
    </channel>
</rss>