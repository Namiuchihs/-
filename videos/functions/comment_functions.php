<?php
function display_comments($conn, $video_id){
    $stmt = $conn->prepare("SELECT * FROM comments WHERE video_id=? ORDER BY created_at ASC");
    $stmt->bind_param("i",$video_id);
    $stmt->execute();
    $res = $stmt->get_result();
    echo "<h3>コメント</h3>";
    while($row=$res->fetch_assoc()){
        echo "<div style='border-top:1px solid #ccc;padding:3px;'>";
        echo "<b>".htmlspecialchars($row['username']).":</b> ".htmlspecialchars($row['comment']);
        echo "<br><small>".$row['created_at']."</small></div>";
    }
}
