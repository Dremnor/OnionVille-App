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


$user = $DB->getUser($_SESSION['id']);
$requests = $DB->getAllRequests();


?>

<!doctype html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>H&H App Admin</title>
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
                    <h1>Menu</h1>
                </div>
                <div class="p-4 mt-4 center color1-bg rounded-1">
                    <a href="/php/requestitem.php" class="buttonStyle">Zamów</a>
                    
                </div>
            </div>
    </div>
    <div>
    <div class="mt-4 p-5 rounded-3 center color1-bg text-center mx-auto jumbo-standard" style="width: 65rem;">
            <h1>ZADANIE</h1>
            <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
            <div class="container text-center mx-auto">
                <div class="row mx-auto">

                </div>
            </div>
        </div>
    </div>

</body>

</html>
<?php
$DB->closeDBConnection();
?>