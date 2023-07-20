<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

const MESSAGES_FILE = __DIR__. "/data/messages.json";

require_once("MessagesStorage.php");

$messagesStorage = new MessagesStorage();

$messagesStorage->createMessagesFileIfNotExists();

$messagesArray = $messagesStorage->getMessages();

$theme = $_COOKIE["theme"] ?? null;

if(!isset($_SESSION["login"]) && isset($_POST["user_login"])){
    $_SESSION["login"] = $_POST["user_login"];
} 

$message = $_POST["user_message"] ?? null;
$login = $_SESSION["login"] ?? null;

if(isset($_POST["user_message"]) && $login) {
   

    if($message == "/set_night_theme"){
        setcookie("theme", "night");
        $theme = "night";
    } else if ($message == "/set_light_theme") {
        setcookie("theme", null, -1);
        $theme = null;
    } else {
        $newMessage = [
            'message' =>$message,
            'login' => $login,
            'time' => time()

        ];
        $messagesStorage->addMessage($newMessage);
    }
   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чатик</title>
    <style>
        body{
          <?php
          if($theme == "night"){
            echo "color: #FFF; background: #003";
          } else if($theme == "light") {
            echo "color: #000; background: #eee";
          }
          ?>
        }

    </style>
</head>
<body>
    <?php if (isset($_SESSION["login"])){
      foreach($messagesStorage->getMessages() as $message){
      echo "<p>". htmlspecialchars($message['login']) . "</p>";
          echo "<p>". htmlspecialchars($message['message']). "</p>";
      echo "<p>". date("d.m.Y H:i", $message['time']) . "</p>";
      }
      echo '<form method="post" action="">
      <input type="text" name="user_message">
      <input type="submit">
  </form>';
    } else {
        echo '<form method="post" action="">
        <input type="text" name="user_login">
        <input type="submit" value="Login"> 
    </form>';
         }?>


</body>
</html>