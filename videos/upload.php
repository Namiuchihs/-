<?php
session_start();
include('config.php');
include('functions/video_functions.php');
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$errors = [];
$success = "";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $title = $_POST['title'] ?? '';
    $desc  = $_POST['description'] ?? '';
    $tags  = explode(',',$_POST['tags'] ?? '');
    $user_id = $_SESSION['user_id'] ?? 0;

    $errors = validate_upload($conn,$user_id,$_FILES['video'],$tags);
    if(empty($errors)){
        $filename = uniqid().".mp4";
        move_uploaded_file($_FILES['video']['tmp_name'], "uploads/".$filename);
        $sm_id = save_video($conn,$user_id,$title,$desc,$filename,$tags);
        $success = "動画がアップロードされました: sm$sm_id";
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head><meta charset="UTF-8"><title>動画アップロード</title>
<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<?php include('../templates/header.php'); include('../templates/nav.php'); ?>
<div class="container">
<h2>動画アップロード</h2>
<?php
if($errors){ foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; }
if($success) echo "<p style='color:green;'>$success</p>";
?>
<form method="POST" enctype="multipart/form-data">
タイトル:<br><input type="text" name="title" required><br>
説明:<br><textarea name="description"></textarea><br>
タグ（カンマ区切り）:<br><input type="text" name="tags"><br>
動画ファイル (mp4, 最大3分, 720p):<br><input type="file" name="video" accept="video/mp4" required><br>
<input type="submit" value="アップロード">
</form>
</div>
<?php include('../templates/footer.php'); ?>
</body>
</html>
