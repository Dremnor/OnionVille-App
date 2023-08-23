<?php
session_start();
$discord_url = "https://discord.com/api/oauth2/authorize?client_id=1139584563379703902&redirect_uri=http%3A%2F%2Fonionville.space%2Fphp%2Fprocess-auth.php&response_type=code&scope=identify%20guilds%20email";
header("Location: $discord_url");
exit();