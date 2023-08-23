<?php
include_once('/php/includes/debug.php');
session_start();
include_once('/php/includes/utils.php');
include_once('/php/checks/oficer_check.php');


$DB = new DB;
$DB->INIT();

if (isset($_GET['id']) && isset($_GET['status'])) {
    $DB->setVerify(!$_GET['status'], $_GET['id']);
}
$users = $DB->getAllUsers();



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
                <div class="">
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
    <div class="mt-4 mw-50 md-50 p-5 rounded-3 center color1-bg text-center mx-auto jumbo-standard">
        <table class="table table-striped table-dark">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nick</th>
                    <th scope="col">Status</th>
                    <th scope="col">Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <th scope="row"><?= $user['id'] ?></th>
                        <td><?= $user['discord_global_name'] ?></td>
                        <td><?= getStatusName($user['verify']) ?></td>
                        <td>
                            <?php
                                if ($user['verify']) {
                                    if(isset($_SESSION['Lider']) || isset($_SESSION['Admin'])){
                                        echo '<a href="?id=' . $user['id'] . '&status=' . $user['verify'] . '" class="btn btn-danger" role="button">Zablokuj</a>';
                                    }else{
                                        echo '<a href="?id=' . $user['id'] . '&status=' . $user['verify'] . '" class="btn btn-danger disabled" role="button">Zablokuj</a>';
                                    } 
                                        
                                } else {
                                        echo '<a href="?id=' . $user['id'] . '&status=' . $user['verify'] . '" class="btn btn-success">Odblokuj</a>';
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>