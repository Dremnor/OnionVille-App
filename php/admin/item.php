<?php
include_once('/php/includes/debug.php');
session_start();
include_once('/php/includes/utils.php');
include_once('/php/checks/oficer_check.php');



$DB = new DB;
$DB->INIT();

$professions = $DB->getAllProfessions();
$users = $DB->getAllUsers();

if (count($_POST) > 0 && count($_FILES) > 0) {



    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";








    $target_dir = '/php/uploads/icons/';
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            $uploadOk = 0;
        }

        if ($_FILES["fileToUpload"]["size"] > 50000) {
            $uploadOk = 0;
        }

        if ($imageFileType != "png") {
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $DB->addItemToDatabase($_POST['name'], $_FILES["fileToUpload"]["name"], $DB->getProfessionId($_POST['profession']));
            }
        }
    }
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

    <link rel="stylesheet" href="../../css/styles.css?v=1.0">
</head>

<body>
    <nav class="navbar navbar-dark color1-bg">
        <div class="container-fluid">
            <a href="/php/admin/admin.php">
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
        <?php if (isset($_SESSION['login'])) : ?>
            <div class="side-menu side-menu-left">
                <div class="p-4 mt-4 center color1-bg rounded-1">
                    <h1>Menu</h1>
                </div>

                <div class="p-4 mt-4 center color1-bg rounded-1">
                    <?php if (hasProfession("Admin")) echo '<a href="http://onionville.space/php/admin/professions.php" class="buttonStyle color6">Professions</a>'; ?>
                    <?php if (hasProfession("Admin")) echo '<a href="http://onionville.space/php/admin/session.php" class="buttonStyle color6">Session</a>'; ?>
                    <?php if (hasProfession("Admin") || hasProfession('Lider')) echo '<a href="http://onionville.space/php/admin/request.php" class="buttonStyle color6">Requests</a>'; ?>
                    <?php if (hasProfession("Admin") || hasProfession('Oficer') || hasProfession('Lider')) echo '<a href="http://onionville.space/php/admin/item.php" class="buttonStyle color6">Add Items</a>'; ?>
                    <?php if (hasProfession("Admin") || hasProfession('Oficer') || hasProfession('Lider')) echo '<a href="http://onionville.space/php/admin/itemlist.php" class="buttonStyle color6">Item List</a>'; ?>
                    <?php if (hasProfession("Admin") || hasProfession('Oficer') || hasProfession('Lider')) echo '<a href="http://onionville.space/php/admin/users.php" class="buttonStyle color6">Users</a>'; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
    <div>

        <div class="mt-4 mw-50 md-50 p-5 rounded-3 center color1-bg text-center mx-auto jumbo-standard">
            <form class="color4 mx-auto" method="post" enctype="multipart/form-data">
                <h1>PROFESSIONS</h1>
                <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
                <div class="container text-start">
                    <div class="row">
                        <?php
                        $index = 0;
                        foreach ($professions as $prof) {
                            if (!in_array($prof['id'], PROFESSIONS_EXCLUDE)  || (hasProfession('Admin') || hasProfession('Lider')) && !in_array($prof['id'], PROFESSIONS_EXCLUDE_OFICER)) {
                                echo "<div class=\"col-sm\">";
                                echo "<input type=\"radio\" id=\"prof_" . $prof['id'] . "\" name=\"profession\" value=\"" . $prof['name'] . "\">";
                                echo "<label class=\"m-1\" for=\"prof_" . $prof['id'] . "\">" . $prof['name'] . "</label><br>";
                                echo "</div>";
                                $index++;
                                if ($index % 3 == 0) {
                                    echo "</div>";
                                    echo "<div class=\"row\">";
                                }
                            }
                        }
                        if ($index % 3 != 0) {
                            for ($i = 0; $i < 3 - $index % 3; $i++) {
                                echo "<div class=\"col-sm\">";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <h1 class="mt-5">ITEM ICON</h1>
                <hr class="border border-light border-2 opacity-75 w-75 mx-auto">
                <label for="fileToUpload">Select Icon:</label>
                <input type="file" name="fileToUpload" id="fileToUpload">
                </br>
                <label for="name">Item Name:</label>
                <input class="mt-3" name="name" type="text" value="">
                </br>
                <input class="mt-3" name="submit" type="submit" value="Send">
            </form>
        </div>


    </div>

</body>

</html>
<?php
$DB->closeDBConnection();
?>