<?php
include('config.php');
include('functions/video_functions.php');
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$videos = get_videos_by_popularity($conn);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head><meta charset="UTF-8"><title>人気動画ランキング</title>
<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<?php include('../templates/header.php'); include('../templates/nav.php'); ?>
<div class="container">
<h2>人気動画ランキング</h2>
<?php
if(!$videos){ echo "<p>動画はまだありません。</p>"; }
else{
    $rank=1;
    foreach($videos as $video){
        $sm_id = "sm".$video['id'];
        $url = ($video['deleted_by_admin']||$video['deleted_by_user']) ? "deleted.php?sm=$sm_id" : "video.php?sm=$sm_id";
        echo "<div style='border:1px solid #ccc;margin:5px;padding:5px;'>";
        echo "#$rank <a href='$url'>".htmlspecialchars($video['title'])."</a> (再生: ".$video['views'].")";
        echo "</div>";
        $rank++;
    }
}
?>
</div>
<?php include('../templates/footer.php'); ?>
</body>
</html>
