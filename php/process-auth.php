<?php
include_once('/php/includes/debug.php');
session_start();
include_once('/php/includes/utils.php');
include_once('/php/db/db.php');

if (!is_writable(session_save_path())) {
  echo 'Session path "'.session_save_path().'" is not writable for PHP!'; 
}


$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';
$revokeURL = 'https://discord.com/api/oauth2/token/revoke';
$guildsURL = 'https://discord.com/api/users/@me/guilds';

if (isset($_GET['code'])) {
  $_SESSION['code'] = $_GET['code'];
  $_SESSION['login'] = true;

  $payload = array(
    'code' => $_SESSION['code'],
    'client_id' => '1139584563379703902',
    'client_secret' => 'IVRSYNeH671mGn0rSabS67rhm18YgCOO',
    'grant_type' => 'authorization_code',
    'redirect_uri' => 'http://onionville.space/php/process-auth.php',
  );
  $payload_string = http_build_query($payload);


  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $tokenURL);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

  $result = curl_exec($ch);
  curl_close($ch);

  if ($result) {
    echo $result;
    $result = json_decode($result, true);

    $_SESSION['access_token'] = $result['access_token'];
    $acces_token = $result['access_token'];

    $header = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $_SESSION['access_token']);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_URL, $apiURLBase);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //download user data
    $result2 = curl_exec($ch);
    $result2 = json_decode($result2, true);

    //download user guilds
    curl_setopt($ch, CURLOPT_URL, $guildsURL);
    $guilds = curl_exec($ch);
    $guilds = json_decode($guilds, true);

    $flaga = false;
    foreach($guilds as $guild){
      if($guild['id'] == '839841958544670731'){
        $flaga = true;
      }
    }
    if($flaga){
      $DB = new DB;
      $DB->INIT();
      
      if(!$DB->checIfUserExist($result2['id'])){
        $DB->addUserToDatabase($result2);
        $_SESSION['avatarpng'] = "https://cdn.discordapp.com/avatars/".$result2['id']."/".$result2['avatar'].".jpg" ;
        $_SESSION['avatar'] = $result2['avatar'];
        $_SESSION['name'] = $result2['global_name'];
        $_SESSION['id'] = $result2['id'];
        $_SESSION['verify'] = 0;
        $_SESSION['professions'] = "";
    
        $DB->closeDBConnection();
        header("Location: /");
        exit();
      }else{
        $user_data = $DB->getuser($result2['id']);
        if ($user_data['discord_avatar'][0] == 'a') {
          $_SESSION['avatarpng'] = "https://cdn.discordapp.com/avatars/".$user_data['discord_id']."/".$user_data['discord_avatar'].".gif" ;
        }else{
          $_SESSION['avatarpng'] = "https://cdn.discordapp.com/avatars/".$user_data['discord_id']."/".$user_data['discord_avatar'].".png" ;
        }
        $_SESSION['avatar'] = $user_data['discord_avatar'];
        $_SESSION['name'] = $user_data['discord_global_name'];
        $_SESSION['id'] = $user_data['discord_id'];
        $_SESSION['verify'] = $user_data['verify'];
        $_SESSION['professions'] = $user_data['professions'];
        setProfessionSessionVariables($user_data['professions'],$result2['id']);
        header("Location: /");
        exit();
      }
    }else{
      header("Location: /php/error/perm-error-server.php");
      die();
    }
    




  } else {
    echo curl_error($ch);
  }

} else {
  header("Location: http://onionville.space/php/error/login-error.php");
  exit();
}