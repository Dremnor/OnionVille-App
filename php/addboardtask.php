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
$items = $DB->getAllItems();


if (isset($_GET['selected_id'])) {
    $_SESSION['selected_id'] = $_GET['selected_id'];
}

if (isset($_POST['item_name'])) {
    $_SESSION['item_name'] = $_POST['item_name'];
}

if (isset($_POST['item_type'])) {
    $_SESSION['item_type'] = $_POST['item_type'];
}


if (isset($_POST['task_desc'])){

    $user = $DB->getUser($_SESSION['id']);
    $item = $DB->getItem($_SESSION['selected_id']);

    $data['item_id'] = $_SESSION['selected_id'];
    $data['description'] = $_POST['task_desc'];
    $data['location'] = $_POST['task_loc'];
    $data['amount'] = $_POST['amount'];
    $data['creator_id'] = $user['id'];

    $DB->addBoardTaskToDatabase($data);

    sendDiscordMessageEmbed($item['name'], $_POST['task_desc'],$item['image'], "<@".$user['discord_id'].">",$_POST['task_loc'],$_POST['amount']);

    unset($_SESSION['selected_id']);
    unset($_SESSION['item_name']);
    unset($_SESSION['item_type']);
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
    <nav class="navbar navbar-dark color1-bg">
        <div class="container-fluid">
            <a href="/">
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

        <div class="mt-4 mw-50 md-50 p-5 rounded-3 center color1-bg text-center mx-auto jumbo-standard " style="width: 86rem;">

            <h1 class="mt-5">DOSTEPNE PRZEDMIOTY</h1>
            <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
            <div>
                <form method="post">
                    <label class="color5" for="item_name">Nazwa Przedmiotu:</label>
                    <?php if (isset($_SESSION['item_name'])) : ?>
                        <input type="text" name="item_name" value="<?= $_SESSION['item_name'] ?>">
                    <?php else : ?>
                        <input type="text" name="item_name">
                    <?php endif; ?>

                    <label class="color5" for="item_type">Typ:</label>
                    <select name="item_type">
                        <option value="1" <?= (isset($_SESSION['item_type']) && $_SESSION['item_type'] == 1 ? "selected" : "") ?>>Wszystkie</option>
                        <option value="2" <?= (isset($_SESSION['item_type']) && $_SESSION['item_type'] == 2 ? "selected" : "") ?>>Przedmioty</option>
                        <option value="3" <?= (isset($_SESSION['item_type']) && $_SESSION['item_type'] == 3 ? "selected" : "") ?>>Zadania</option>
                    </select>
                    <input type="submit" value="szukaj">
                </form>
            </div>

            <div class="container text-center mx-auto">
                <div class="row mx-auto">
                    <?php foreach ($items as $item) : ?>
                        <?php
                        $namecheck = true;
                        $typecheck = true;
                        if (isset($_SESSION['item_name'])) {
                            $namecheck = str_contains(strtolower($item['name']), strtolower($_SESSION['item_name']));
                        }
                        if (isset($_SESSION['item_type'])) {
                            if ($_SESSION['item_type'] == 3 && $item['profession_id'] != 18) {
                                $typecheck = false;
                            }

                            if ($_SESSION['item_type'] == 2 && $item['profession_id'] == 18) {
                                $typecheck = false;
                            }
                        }
                        ?>
                        <?php if ($item['status'] && $namecheck && $typecheck) : ?>
                            <div class="card text-center color3-bg mt-4 mx-auto card-border" style="width: 12rem;">
                                <div class="image_card">
                                    <img class="card-img-top mx-auto image-border image_card" style="height: 10rem; width: 10rem" src="/php/uploads/icons/<?= $item['image'] ?>" alt="Card image cap">
                                </div>
                                <hr class="border rounded border-warning border-2 opacity-75 w-75 mx-auto">
                                <div class="card-body">
                                    <h5 class="card-title color4"><?= $item['name'] ?></h5>
                                    <div class="card-button">
                                        <?php if (isset($_SESSION['selected_id']) && $_SESSION['selected_id'] == $item['id']) : ?>
                                            <a href="?selected_id=<?= $item['id'] ?>" class="btn btn-danger disabled">Wybrany</a>
                                        <?php else : ?>
                                            <a href="?selected_id=<?= $item['id'] ?>" class="btn btn-success">Wybierz</a>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                            <?php
                            $index++;
                            if ($index % 6 == 0) {
                                echo "</div>";
                                echo "<div class=\"row mx-auto\">";
                            }
                            ?>

                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <? if (isset($_SESSION['selected_id'])) : ?>
                <h1 class="mt-5">Ilość i Treść</h1>
                <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
                <form method="post">
                    <label class="mt-3 color5" for="task_desc">Opis zadania:</label></br>
                    <textarea id="area" name="task_desc" rows="4" cols="50"></textarea></br>
                    <label class="mt-3 color5" for="amount">Wymagana ilość(0 = brak ilośći):</label></br>
                    <input type="number" value="0" name="amount">
                    <h1 class="mt-5">Lokalizacja</h1>
                    <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
                    <label class="mt-3 color5" for="task_loc">Lokalizacja(pozycja albo opis):</label></br>
                    <textarea id="area" name="task_loc" rows="4" cols="50"></textarea></br>

                    <input type="submit" name="addbo" value="Wystaw">
                </form>
            <? endif; ?>
        </div>


    </div>

</body>

</html>
<?php
$DB->closeDBConnection();
?>



