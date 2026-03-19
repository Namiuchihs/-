<?php
function get_next_sm_id($conn){
    $result = $conn->query("SELECT MAX(id) as max_id FROM videos");
    $row = $result->fetch_assoc();
    return intval($row['max_id']) + 1;
}

function save_video($conn, $user_id, $title, $description, $filename, $tags){
    $sm_id = get_next_sm_id($conn);
    $stmt = $conn->prepare("INSERT INTO videos (id,user_id,title,description,filename) VALUES (?,?,?,?,?)");
    $stmt->bind_param("iisss", $sm_id, $user_id, $title, $description, $filename);
    $stmt->execute();
    save_tags($conn, $sm_id, $tags);
    return $sm_id;
}

function save_tags($conn, $video_id, $tags){
    foreach($tags as $tag){
        $stmt = $conn->prepare("INSERT INTO video_tags (video_id, tag) VALUES (?,?)");
        $stmt->bind_param("is", $video_id, $tag);
        $stmt->execute();
    }
}

function get_video($conn, $video_id){
    $stmt = $conn->prepare("SELECT * FROM videos WHERE id=?");
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function get_video_tags($conn, $video_id){
    $stmt = $conn->prepare("SELECT tag FROM video_tags WHERE video_id=?");
    $stmt->bind_param("i",$video_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $tags = [];
    while($row=$res->fetch_assoc()) $tags[]=$row['tag'];
    return $tags;
}

function get_videos_by_tag($conn, $tag){
    $stmt = $conn->prepare("SELECT v.* FROM videos v JOIN video_tags t ON v.id=t.video_id WHERE t.tag=? ORDER BY v.id ASC");
    $stmt->bind_param("s", $tag);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function get_videos_by_popularity($conn){
    $res = $conn->query("SELECT * FROM videos ORDER BY views DESC LIMIT 50");
    return $res->fetch_all(MYSQLI_ASSOC);
}

function search_videos($conn, $query){
    $q = "%".$query."%";
    $stmt = $conn->prepare("SELECT * FROM videos WHERE title LIKE ? OR description LIKE ? ORDER BY id DESC");
    $stmt->bind_param("ss", $q,$q);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function validate_upload($conn,$user_id,$file,$tags){
    $errors=[];
    if(!ALLOW_R18 && in_array('R18',$tags)) $errors[]="R18動画は禁止です";
    $today = date('Y-m-d');
    $stmt = $conn->prepare("SELECT COUNT(*) as c FROM videos WHERE user_id=? AND DATE(created_at)=?");
    $stmt->bind_param("is",$user_id,$today);
    $stmt->execute();
    $res=$stmt->get_result()->fetch_assoc();
    if($res['c']>=MAX_UPLOAD_PER_DAY) $errors[]="1日あたりの投稿上限に達しています";
    // ファイル長・解像度チェックはフロントまたはサーバー側の動画解析で対応
    return $errors;
}
