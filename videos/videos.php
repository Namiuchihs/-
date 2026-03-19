<?php
session_start();
include('config.php');
include('functions/video_functions.php');
include('functions/comment_functions.php');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($conn->connect_error) die("DB接続エラー: ".$conn->connect_error);

$sm_id = $_GET['sm'] ?? '';
$video_id = intval(substr($sm_id,2));
$video = get_video($conn, $video_id);

if(!$video || $video['deleted_by_admin'] || $video['deleted_by_user']){
    header("Location: deleted.php?sm={$sm_id}");
    exit;
}

// 再生数カウント
$conn->query("UPDATE videos SET views=views+1 WHERE id={$video_id}");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head><meta charset="UTF-8"><title><?php echo htmlspecialchars($video['title']); ?></title>
<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<?php include('../templates/header.php'); include('../templates/nav.php'); ?>
<div class="container">
<h2><?php echo htmlspecialchars($video['title']); ?> (<?php echo $sm_id; ?>)</h2>
<video width="720" controls>
<source src="uploads/<?php echo htmlspecialchars($video['filename']); ?>" type="video/mp4">
お使いのブラウザでは再生できません。
</video>
<p>タグ: <?php echo implode(', ', get_video_tags($conn,$video_id)); ?></p>
<p>再生数: <?php echo $video['views']+1; ?></p>

<?php if(ENABLE_COMMENTS){ display_comments($conn, $video_id); } ?>
</div>
<?php include('../templates/footer.php'); ?>
</body>
</html>
