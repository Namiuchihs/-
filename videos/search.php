<?php
include('config.php');
include('functions/video_functions.php');
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$query = trim($_GET['q'] ?? '');
$videos = $query ? search_videos($conn, $query) : [];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head><meta charset="UTF-8"><title>検索結果: <?php echo htmlspecialchars($query); ?></title>
<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<?php include('../templates/header.php'); include('../templates/nav.php'); ?>
<div class="container">
<h2>検索結果: <?php echo htmlspecialchars($query); ?></h2>
<?php
if(!$query){ echo "<p>検索ワードを入力してください。</p>"; }
elseif(!$videos){ echo "<p>動画は見つかりませんでした。</p>"; }
else{
    foreach($videos as $video){
        $sm_id="sm".$video['id'];
        $url = ($video['deleted_by_admin']||$video['deleted_by_user']) ? "deleted.php?sm=$sm_id" : "video.php?sm=$sm_id";
        echo "<div style='border:1px solid #ccc;margin:5px;padding:5px;'>";
        echo "<a href='$url'>".htmlspecialchars($video['title'])."</a> | タグ: ".implode(', ', get_video_tags($conn,$video['id']));
        echo "</div>";
    }
}
?>
</div>
<?php include('../templates/footer.php'); ?>
</body>
</html>
