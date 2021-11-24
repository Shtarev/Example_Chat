<?php
if (isset($_POST['message'])){
    $message = $_POST['message'];
    $chat = $_POST['chat'];
    file_put_contents($chat.'.txt', $message, FILE_APPEND);
    file_put_contents($chat.'.json', json_encode(file_get_contents($chat.'.txt'), JSON_UNESCAPED_UNICODE));
}