<?php
// データベース設定
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'kawanoetaikodai');

// 投稿制限
define('MAX_UPLOAD_PER_DAY', 2);
define('MAX_VIDEO_DURATION', 180); // 秒
define('MAX_VIDEO_RESOLUTION', 720); // px
define('ALLOW_R15', true);
define('ALLOW_R18', false);

// コメント機能
define('ENABLE_COMMENTS', true);
