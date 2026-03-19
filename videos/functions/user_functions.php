<?php
function get_user($conn,$user_id){
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
