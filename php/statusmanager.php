<?php
include_once('/php/includes/debug.php');
session_start();
include_once('/php/includes/utils.php');
include_once('/php/checks/login_check.php');
include_once('/php/checks/verify_check.php');

$DB = new DB;
$DB->INIT();

$id = $_GET['id'];
$status = $_GET['status'];
$mode = $_GET['mode'];
$request = $DB->getRequests($id);
$discord_id = $_SESSION['id'];
$user_requester = $DB->getUserById($request['requester_id']);
$item = $DB->getitem($request['item_id']);
$profession = $DB->getProfession($item['profession_id']);

switch ($mode) {
    case 1:     
        $user_crafter = $DB->getUser($_SESSION['id']);
        $DB->setRequestStatus(2, $id);
        $DB->setRequestCrafter($user_crafter['id'], $id);
        sendDiscordMessageTookRequest($item['name'],$user_crafter['discord_id'],$user_requester['discord_id']);
        break;
    case 2:
        $DB->setRequestStatus(3, $id);
        $user_crafter = $DB->getUserById($request['crafter_id']);
        sendDiscordMessageFinishedRequest($item['name'],$user_crafter['discord_id'],$user_requester['discord_id']);
        break;
    case 3:
        $DB->setRequestStatus(6, $id);
        $DB->setFinishDate($id);
        break;
    case 4:
        $DB->setRequestStatus(7, $id);
        break;
    default:
        break;
}
flush();
header("Location: " . $_GET['redirect'] . "");
die('should have redirected by now');
