<?php
include('config.php');
include('functions/video_functions.php');
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$sm_id = $_GET['sm'] ?? '';
$video_id = intval(substr($sm_id,2));
$video = get_video($conn,$video_id);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head><meta charset="UTF-8"><title>動画削除 - <?php echo $sm_id; ?></title>
<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<?php include('../templates/header.php'); include('../templates/nav.php'); ?>
<div class="container">
<h2>動画が削除されています</h2>
<?php
if(!$video){
    echo "<p>この動画（{$sm_id}）は存在しません、または削除済みです。</p>";
}else{
    echo $video['deleted_by_admin']
        ? "<p>この動画（{$sm_id}）は運営により削除されました。</p>"
        : "<p>この動画（{$sm_id}）は投稿者により削除されました。</p>";
}
?>
<a href="videos.php">動画一覧に戻る</a>
</div>
<?php include('../templates/footer.php'); ?>
</body>
</html>
