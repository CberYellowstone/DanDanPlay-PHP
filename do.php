<?php
include_once 'function.php';

mkpicForRoot($video_root_path);
saveVideoInformationForRoot($video_root_path);
downloadCommentForRoot($video_root_path);

echo("任务完成");



?>