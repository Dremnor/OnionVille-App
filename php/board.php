<?php
include_once('/php/includes/debug.php');
session_start();
include_once('/php/includes/utils.php');
include_once('/php/checks/login_check.php');
include_once('/php/checks/verify_check.php');


$DB = new DB;
$DB->INIT();
$items;
$selected_item;
$index = 0;

//init data
$user = $DB->getUser($_SESSION['id']);
$tasks = $DB->getAllBoardTasks();
$task = null;



if (isset($_GET['id'])) {
    $task = $DB->getBoardTask($_GET['id']);
} else {
    if (count($tasks) > 0) {
        $task = $tasks[0];
    }
}

if (isset($_GET['adduser'])) {
    $data;
    if ($task['users_list'] != "") {
        $data = json_decode($task['users_list']);
    } else {
        $data = array();
    }
    if (!in_array($user['id'], $data)) {
        array_push($data, $_GET['adduser']);
        $DB->setTaskUsers(json_encode($data), $_GET['id']);
        $task = $DB->getBoardTask($_GET['id']);
    }
}

if (isset($_GET['actuser'])) {
    $data;
    if ($task['users_act'] != "") {
        $data = json_decode($task['users_act']);
    } else {
        $data = array();
    }
    if (!in_array($_GET['actuser'], $data)) {
        array_push($data, $_GET['actuser']);
        $DB->setTaskAprUsers(json_encode($data), $_GET['id']);
        $task = $DB->getBoardTask($_GET['id']);
    }
}

?>

<!doctype html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>H&H App</title>
    <meta name="description" content="A simple app for villager.">
    <meta name="author" content="Dremnor">

    <meta property="og:title" content="H&H App Admin">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://onionville.space/">
    <meta property="og:description" content="Simple app for request managment .">
    <meta property="og:image" content="image.png">

    <link rel="icon" href="/favicon.ico">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <link rel="stylesheet" href="/css/styles.css?v=1.0">
</head>

<body>
    <?= include_once('/php/includes/nav.php') ?>

    <div>
        <div class="side-menu side-menu-left">
            <div class="p-4 mt-4 center color1-bg rounded-1">
                <h1>Aktywne Zadania</h1>
            </div>
            <div class="p-4 mt-4 center color1-bg rounded-1">
                <?php foreach ($tasks as $tas) : ?>
                    <?php
                    if ($tas['active']) {
                        $item =  $DB->getItem($tas['item_id']);
                        echo '<a href="?id=' . $tas['id'] . '" class="buttonStyle">Zadanie:' . $item['name'] . '</a>';
                    }
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div>
        <?php
        $name = $DB->getItem($task['item_id'])['name'];
        $image = $DB->getItem($task['item_id'])['image'];
        ?>
        <div class="mt-4 p-5 rounded-3 center color1-bg text-center mx-auto jumbo-standard" style="width: 65rem;">
            <h1><?= $name ?></h1>
            <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
            <?php
            $users;
            $usersact;
            if ($task['users_list'] != "") {
                $users = json_decode($task['users_list']);
            } else {
                $users = array();
            }

            if ($task['users_apr'] != "") {
                $usersact = json_decode($task['users_apr']);
            } else {
                $usersact = array();
            }

            $index = 1;
            ?>
            <img src="/php/uploads/icons/<?= $image ?>" alt="">
            <h1>
                Opis
            </h1>
            <p>
                <?= $task['description'] ?>
            </p>
            <h1>
                Lokacja
            </h1>
            <p>
                <?= $task['location'] ?>
            </p>
            <h1>
                Ilość
            </h1>
            <p>
                <?= $task['amount'] ?>
            </p>
            <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
            <h1>
                Uczestnicy
            </h1>
            <table class="table table-striped table-dark">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nick</th>
                        <th scope="col">Status</th>
                        <th scope="col">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $ucz) : ?>
                        <?php
                        $data = $DB->getUserById($ucz);
                        ?>
                        <tr>
                            <th scope="row"><?= $index ?></th>
                            <td><?= $data['discord_global_name'] ?></td>
                            <td>
                                <?php
                                if (!in_array($data['id'], $usersact)) {
                                    echo "<span class=\"color-red\">Niepotwierdzony</span>";
                                } else {
                                    echo "<span class=\"color-green\">Potwierdzony</span>";
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!in_array($data['id'], $usersact) && (isset($_SESSION['Oficer']) || isset($_SESSION['Admin']) || isset($_SESSION['Lider']))) {
                                    echo '<a href="?id=' . $task['id'] . '&actuser=' . $ucz . '" class="btn btn-success">Obecny</a>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php $index++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
            <?php
            if (!in_array($user['id'], $users)) {
                echo '<a href="?id=' . $task['id'] . '&adduser=' . $user['id'] . '" class="buttonStyle">Zgłoś się</a>';
            }

            if ($task != null && $task['creator_id'] == $user['id']) {
                echo '<a href="?id=' . $task['id'] . '&finish=1" class="buttonStyle">Zakończ zadanie!</a>';
            }

            ?>
        </div>
    </div>

</body>

</html>
<?php
$DB->closeDBConnection();
?>