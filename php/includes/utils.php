<?php

include_once('/php/db/db.php');
include_once('/config.php');

function updateSessionData($discord_id, $old_data)
{
    $DB = new DB;
    $DB->INIT();
    $user = $DB->getUser($discord_id);

    if ($old_data['professions'] != $user['professions']) {
        setProfessionSessionVariables($user['professions']);
        $_SESSION['professions'] = $user['professions'];
    }

    if ($old_data['verify'] != $user['verify']) {
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
        $buttons .= "<a href=\"/php/admin/admin.php\" class=\"buttonStyle color6\">Panel</a>";
    }


    return $buttons;
}

function printRequestButtons($status, $redirect, $mode, $id)
{
    switch ($status) {
        case 1:
            return '<a href="/php/statusmanager.php?id=' . $id . '&mode=' . $mode . '&redirect=' . $redirect . '&status=' . $status . '" class="buttonStyleCard">Rozpocznij</a>';
            break;
        case 2:
            return '<a href="/php/statusmanager.php?id=' . $id . '&mode=' . $mode . '&redirect=' . $redirect . '&status=' . $status . '" class="buttonStyleCard">Gotowe do odbioru.</a>';
            break;
        case 3:
            return '<a href="/php/statusmanager.php?id=' . $id . '&mode=' . $mode . '&redirect=' . $redirect . '&status=' . $status . '" class="buttonStyleCard">Zakończ.</a>';
            break;
        case 4:

            break;
        case 5:
            return '<a href="/php/statusmanager.php?id=' . $id . '&mode=4&redirect=' . $redirect . '&status=' . $status . '" class="buttonStyleCard">Usuń</a>';
            break;
        case 6:
            return '<a href="/php/statusmanager.php?id=' . $id . '&mode=4&redirect=' . $redirect . '&status=' . $status . '" class="buttonStyleCard">Usuń</a>';
            break;
        case 7:
            return '<a href="/php/statusmanager.php?id=' . $id . '&mode=4&redirect=' . $redirect . '&status=' . $status . '" class="buttonStyleCard">Usuń</a>';
            break;

        default:

            break;
    }
}

function getStatusName($status)
{
    if ($status) {
        return "Zweryfikowany";
    } else {
        return "Niezweryfikowany";
    }
}

function printStatus()
{
}

function sendDiscordMessageNewRequest($item_name, $role, $player, $amount)
{
    $webhookurl = NOTIFICATION_HOOK;

    $json_data = json_encode([
        "content" => "",
        "tts" => false,
        "embeds" => [
            [
                "id" => 652627557,
                "title" => "NOWE ZAMÓWIENIE!",
                "description" => "Mieszkaniec <@" . $player . "> wystawił zamówienie na " . $item_name,
                "color" => 3456795,
                "fields" => [
                    [
                        "id" => 670306411,
                        "name" => "Ilość:",
                        "value" => $amount
                    ],
                    [
                        "id" => 670306411,
                        "name" => "Crafterzy:",
                        "value" => $role
                    ]
                ],
                "url" => "http://onionville.space/php/requestlist.php"
            ]
        ],
        "components" => [],
        "actions" => [],
        "username" => "OnionVille App"
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

function sendDiscordMessageTookRequest($item_name, $player, $reciv)
{
    $webhookurl = NOTIFICATION_HOOK;

    $json_data = json_encode([
        "content" => "",
        "tts" => false,
        "embeds" => [
            [
                "id" => 652627557,
                "title" => "ZAMÓWIENIE POBRANE!",
                "description" => "Crafter <@".$player."> rozpoczął produkcje " . $item_name . " dla <@". $reciv.">",
                "color" => 3456795,
                "fields" => [
                ],
                "url" => "http://onionville.space/php/userrequest.php"
            ]
        ],
        "components" => [],
        "actions" => [],
        "username" => "OnionVille App"
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

function sendDiscordMessageFinishedRequest($item_name, $player, $reciv)
{
    $webhookurl = NOTIFICATION_HOOK;

    $json_data = json_encode([
        "content" => "",
        "tts" => false,
        "embeds" => [
            [
                "id" => 652627557,
                "title" => "ZAMÓWIENIE PGOTOWE DO ODEBRANIA!",
                "description" => "<@". $reciv.">! Twój ".$item_name." jest gotowy do odebrania od <@".$player.">",
                "color" => 3456795,
                "fields" => [
                ],
                "url" => "http://onionville.space/php/userrequest.php"
            ]
        ],
        "components" => [],
        "actions" => [],
        "username" => "OnionVille App"
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


function sendDiscordMessageEmbed($title, $desc, $image, $mention_crea, $location, $amount)
{
    $webhookurl = NOTIFICATION_HOOK;
    $json_data = json_encode([
        "content" => "Prosze zgłaszać sie do zadania na stronie",
        "tts" => false,
        "embeds" => [
            [
                "image" => [
                    "url" => "http://onionville.space/php/uploads/icons/" . $image
                ],
                "id" => 652627557,
                "title" => $title,
                "description" => $desc,
                "color" => 2326507,
                "fields" => [
                    [
                        "id" => 343446422,
                        "name" => "Ilość",
                        "value" => $amount
                    ],
                    [
                        "id" => 343446421,
                        "name" => "Lokacja",
                        "value" => $location
                    ],
                    [
                        "id" => 670306412,
                        "name" => "Opiekun Zadania:",
                        "value" => $mention_crea
                    ],
                    [
                        "id" => 670306411,
                        "name" => "Grupa:",
                        "value" => "<@&845013813019148308>"
                    ]
                ],
                "url" => "http://onionville.space/php/board.php"
            ]
        ],
        "components" => [],
        "actions" => [],
        "username" => "OnionVille App"
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
