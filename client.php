<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Сhat_example</title>
</head>
<body>
<?php
$server = "http://yousite.loc/server.php";
file_put_contents('0.json', '""');
$file = glob("*.json");

$chat = '';
$chatjs = 0;
$name = '';
$message = '';
$response = '';
$status = '';

if(isset($_GET['chat'])) {
    $chat = '<input type="hidden" name="chat" value="'.$_GET['chat'].'">';
    $name = 'Enter your name<br><input type="text" name="name" required ><br><br>';
    $message = 'Enter your message<br><input type="text" name="message" required ><br><br><input type="submit" name="submit" value="Submit" >';
    $chatjs = $_GET['chat'];
    if(!is_file($chatjs.'.json')) {
        file_put_contents($chatjs.'.json', '""');
    }
}

if(isset($_POST['chat'])) {
    $chatjs = $_POST['chat'];
    $chat = '<input type="hidden" name="chat" value="'.$_POST['chat'].'">';
    $name = 'Enter your name<br><input type="text" name="name" required ><br><br>';
    $message = 'Enter your message<br><input type="text" name="message" required ><br><br><input type="submit" name="submit" value="Submit" >';
    
    if(isset($_POST['message']) && $_POST['message'] != '' && $_POST['name'] != '') {

        $name = '<input type="hidden" name="name" value="'.$_POST['name'].'">';
        $send = "chat=".$_POST['chat']."&message=Message from: ".$_POST['name']."<br>-- ".$_POST['message']."<br>";

        $connection = curl_init();  
        curl_setopt($connection, CURLOPT_URL, $server);  
        curl_setopt($connection, CURLOPT_POST, 1);  
        curl_setopt($connection, CURLOPT_POSTFIELDS, $send); 
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);  
        $response = curl_exec($connection);  
        curl_close($connection);   
    }
    else {
        $name = 'Enter your name<br><input type="text" name="name" required ><br><br>';
        $message = 'Enter your message<br><input type="text" name="message" required ><br><br><input type="submit" name="submit" value="Submit" >';
        $status = 'You didn\'t enter a name or a message';
    }
}
?>
<details>  
    <summary>Все чаты</summary>  
    <?php
    foreach($file as $value) {
        if($value != '0.json') {
            $link = preg_replace('/\.\w+$/', '', $value);
            echo '<a href="client.php?chat='.$link.'">'.$link.'</a><br>';
        }
    }
    ?>  
</details>

<h3>Online Chat №(<?= $chatjs ?>)</h3>
<p><?= $status ?></p>
<p><a href="client.php?chat=<?=rand(1,1000)?>">Create chat</a></p>
<br>
<form method="post" action="">
    <?= $chat ?>
    <?= $name ?>
    <?= $message ?>
</form>

<div id="response"><?= $response ?></div>

<script>
setInterval(()=>{
fetch("http://test/<?=$chatjs?>.json")  
    .then((response) => {  
        return response.json();  
    })  
    .then((data) => {
        console.log(data)
        if(data) {
            document.getElementById('response').innerHTML = '<p>Response:</p>'+data;
        }
    })  
}, 1000);
</script>
</body>
</html>