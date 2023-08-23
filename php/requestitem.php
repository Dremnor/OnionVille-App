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

if (isset($_POST['profession'])) {
    $items = $DB->getAllItems();
} 

if(isset($_GET['selected_id'])) {
    $selected_item = $DB->getItem($_GET['selected_id']);
}

if (isset($_POST['request'])) {
    $user = $DB->getUser($_SESSION['id']);
    $selected_item = $DB->getItem($_POST['selected_id']);
    $profession = $DB->getProfession($_POST['selected_profession']);
    $DB->addRequestToDatabase($user['id'],$_POST['selected_id'],$_POST['amount'],$_POST['selected_profession']);
    sendDiscordMessage($user['discord_global_name']." wystawił nowe zamówienie na ".$selected_item['name'].".".$profession['role']);
} 

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
    <nav class="navbar navbar-dark color1-bg">
        <div class="container-fluid">
            <a href="http://onionville.space/">
                <h1 class="color4">OnionVille</h1>
            </a>
            <?php if (isset($_SESSION['login'])) : ?>
                <div class="float-right text-center">
                    <h1> <?php echo $_SESSION['name'] ?> </h1>
                    <?php if ($_SESSION['verify']) {
                        echo "<h2 class=\"color7\">Konto Aktywne</h2>";
                    } else {
                        echo "<h2 class=\"color6\">Oczekuje na weryfikacje</h2>";
                    } ?>
                </div>
                <div class="float-right">
                    <img class="rounded-circle float-end" src="<?php echo $_SESSION['avatarpng'] ?>">
                </div>

            <?php else : ?>
                <div>
                    <h1></h1>
                </div>
            <?php endif; ?>
        </div>
    </nav>

    <div>

        <div class="mt-4 mw-50 md-50 p-5 rounded-3 center color1-bg text-center mx-auto jumbo-standard">
            <?php //if (!isset($_POST['profession']) && !isset($_GET['selected_id'])) : ?>
                <form class="color4" method="post">
                    <h1 class="mt-5">WYKONAWCA</h1>
                    <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
                    <label for="user">Profesje:</label></br>
                    <select id="user" name="profession" size="1">
                        <?php
                        foreach ($professions as $profession) {
                            if (!in_array($profession['id'], REQUEST_ITEM_PROFESSIONS_EXCLUDE)) {
                                echo "<option value=\"" . $profession['id'] . "\">" . $profession['name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                    </br>
                    <input class="mt-3" type="submit" value="Szukaj">
                </form>
            <?php //endif; ?>

            <?php if (isset($_POST['profession'])) : ?>
                <h1 class="mt-5">DOSTEPNE PRZEDMIOTY</h1>
                <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
                <div class="container text-start mx-auto">
                    <div class="row mx-auto">
                        <?php foreach ($items as $item) : ?>
                            <?php if ($item['profession_id'] == $_POST['profession'] && $item['status'] ) : ?>
                                <div class="card text-center color3-bg mt-4 mx-2" style="width: 12rem;">
                                    <div class="image_card">
                                        <img class="card-img-top mx-auto my-auto " style="height: 11rem; width: 11rem" src="/php/uploads/icons/<?= $item['image'] ?>" alt="Card image cap">
                                    </div>
                                    <hr class="border rounded border-warning border-2 opacity-75 w-75 mx-auto">
                                    <div class="card-body">
                                        <h5 class="card-title color4"><?= $item['name'] ?></h5>
                                        <div>
                                            <a href="?selected_id=<?= $item['id'] ?>" class="btn btn-success">Wybierz</a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $index++;
                                if ($index % 3 == 0) {
                                    echo "</div>";
                                    echo "<div class=\"row mx-auto\">";
                                }
                                ?>

                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php elseif (isset($_GET['selected_id'])) : ?>
                <div class="mx-auto text-center">
                    <h1 class="mt-5">Wybrany Item</h1>
                    <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
                    <div class="card text-center color3-bg mt-4 mx-auto" style="width: 12rem;">
                        <div class="image_card">
                            <img class="card-img-top mx-auto my-auto " style="height: 11rem; width: 11rem" src="/php/uploads/icons/<?= $selected_item['image'] ?>" alt="Card image cap">
                        </div>
                        <hr class="border rounded border-warning border-2 opacity-75 w-75 mx-auto">
                        <div class="card-body">
                            <h5 class="card-title color4"><?= $selected_item['name'] ?></h5>
                        </div>
                    </div>
                    <FORM class="mt-4 color3" method="post" action="requestitem.php">
                        <h3>Ilość:</h3>
                        <input type="number" name="amount" value="1"></br>
                        <input type="text" name="selected_id" value="<?= $selected_item['id']?>" hidden>
                        <input type="text" name="selected_profession" value="<?= $selected_item['profession_id']?>" hidden>
                        <input class="mt-3" type="submit" value="Zamów" name="request">
                    </FORM>
                </div>

            <?php endif; ?>


        </div>


    </div>

</body>

</html>
<?php
$DB->closeDBConnection();
?>