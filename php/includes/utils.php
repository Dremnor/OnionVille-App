<?php

include_once('/php/db/db.php');
include_once('/config.php');

function updateSessionData($discord_id, $old_data){
    $DB = new DB;
    $DB->INIT();
    $user = $DB->getUser($discord_id);
   
    if($old_data['professions'] != $user['professions']){
        setProfessionSessionVariables($user['professions']);
        $_SESSION['professions'] = $user['professions'];
    }

    if($old_data['verify'] != $user['verify']){
        $_SESSION['verify'] = $user['verify'];
    }

    $DB->closeDBConnection();
}

function setProfessionSessionVariables($professions)
{
    if (isset($professions) && $professions != "") {
        clearProfessionsSessionVariable();
        $DB = new DB;
        $DB->INIT();
        $profs = explode("?", $professions);

        foreach ($profs as $prof) {
            $profession_data = $DB->getProfession($prof);
            $_SESSION[$profession_data['name']] = 1;
        }
        $DB->closeDBConnection();
    }
}

function clearProfessionsSessionVariable()
{
    $DB = new DB;
    $DB->INIT();
    $professions_data = $DB->getAllProfessions();
    foreach ($professions_data as $prof) {
        if (isset($_SESSION[$prof['name']])) {
            unset($_SESSION[$prof['name']]);
        }
    }
    $DB->closeDBConnection();
}

function hasProfession($name)
{
    if (isset($_SESSION[$name])) {
        return true;
    } else {
        return false;
    }
}

function printMenuButtons()
{
    $buttons = "";
    if (hasProfession('Admin') || hasProfession('Lider')) {
        $buttons .= "<a href=\"/php/addboardtask.php\" class=\"buttonStyle\">Dodaj na Tablice</a>";
    }
    if (hasProfession('Admin') || hasProfession('Oficer') || hasProfession('Lider')) {
        $buttons .= "<a href=\"/php/admin/admin.php\" class=\"buttonStyle color6\">Admin Panel</a>";
    }


    return $buttons;
}

function printRequestButtons($status, $redirect, $mode, $id )
{
    switch ($status) {
        case 1:
            return '<a href="/php/statusmanager.php?id='.$id.'&mode='.$mode.'&redirect='.$redirect.'&status='.$status.'" class="buttonStyleCard">Rozpocznij</a>';
            break;
        case 2:
            return '<a href="/php/statusmanager.php?id='.$id.'&mode='.$mode.'&redirect='.$redirect.'&status='.$status.'" class="buttonStyleCard">Gotowe do odbioru.</a>';
            break;
        case 3:
            return '<a href="/php/statusmanager.php?id='.$id.'&mode='.$mode.'&redirect='.$redirect.'&status='.$status.'" class="buttonStyleCard">Zakończ.</a>';
            break;
        case 4:

            break;
        case 5:
            return '<a href="/php/statusmanager.php?id='.$id.'&mode=4&redirect='.$redirect.'&status='.$status.'" class="buttonStyleCard">Usuń</a>';
            break;
        case 6:
            return '<a href="/php/statusmanager.php?id='.$id.'&mode=4&redirect='.$redirect.'&status='.$status.'" class="buttonStyleCard">Usuń</a>';
            break;
        case 7:
            return '<a href="/php/statusmanager.php?id='.$id.'&mode=4&redirect='.$redirect.'&status='.$status.'" class="buttonStyleCard">Usuń</a>';
            break;

        default:

            break;
    }
}

function getStatusName($status){
    if ($status) {
        return "Zweryfikowany";
    }else{
        return "Niezweryfikowany";
    }

}

function printStatus()
{
}

function sendDiscordMessage($message)
{
    $webhookurl = NOTIFICATION_HOOK;

    $timestamp = date("c", strtotime("now"));

    $json_data = json_encode([
        "content" => $message,
        "username" => "OnionVille App",
        "tts" => false
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $ch = curl_init($webhookurl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    curl_close($ch);
}
