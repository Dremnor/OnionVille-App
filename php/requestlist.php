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

$professions = $DB->getAllProfessions();
$user = $DB->getUser($_SESSION['id']);
$requests = $DB->getAllRequests();


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
        <div class="mt-4 p-5 rounded-3 center color1-bg text-center mx-auto jumbo-standard" style="width: 99rem;">
            <h1>LISTA ZAMÓWIEŃ</h1>
            <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
            <div class="container text-center mx-auto">
                <div class="row mx-auto">
                    <?php foreach ($requests as $request) : ?>
                        <?php
                        $profession = $DB->getProfession($request['profession_id']);
                        $crafter = "none";
                        $status = $DB->getStatus($request['status_id']);
                        if (isset($request['crafter_id'])) {
                            $crafter = $DB->getUserById($request['crafter_id'])['discord_global_name'];
                        };
                        $requester = $DB->getUserById($request['requester_id'])['discord_global_name'];
                        ?>
                        <?php if (hasProfession($profession['name']) && $crafter != $_SESSION['name'] && $requester != $_SESSION['name'] && $status['id'] != 5 && $status['id'] != 7 && $status['id'] != 6) : ?>
                            <?php 
                            $item = $DB->getitem($request['item_id']);
                            ?>
                            <div class="card text-center color3-bg mt-4 mx-2 card-border" style="width: 19rem;">
                                <div class="image_card">
                                    <img class="card-img-top mx-auto image-border image_card" style="height: 11rem; width: 11rem" src="/php/uploads/icons/<?= $item['image'] ?>" alt="Card image cap">
                                    <div class="card-status"><?= $status['status_code'] ?></div>
                                </div>
                                <hr class="border rounded border-warning border-2 opacity-75 w-75 mx-auto">
                                <div class="card-body">
                                    <h5 class="card-title color4"><?= $item['name'] ?></h5>
                                    <div class="color5">Ilość: <?=$request['amount'] ?></div>
                                    </p>
                                    <div class="color5">
                                        <h5>Zamawia</h5>
                                        <?= $requester ?>
                                    </div>
                                    <div class="color5">
                                        <h5>Status</h5>
                                        <?= $status['name'] ?>
                                    </div>
                                    <div class="color5">
                                        <h5>Crafter</h5>
                                        <?= $crafter ?>
                                    </div>
                                    <div>
                                        <?php
                                        if ($status['id'] == 1 || $crafter == $_SESSION['name']) {
                                            echo printRequestButtons($request['status_id'], '/php/requestlist.php', 1, $request['id']);
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $index++;
                            if ($index % 4 == 0) {
                                echo "</div>";
                                echo "<div class=\"row mx-auto\">";
                            }
                            ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<?php
$DB->closeDBConnection();
?>