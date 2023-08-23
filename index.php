<?php
include_once('/php/includes/debug.php');
session_start();
?>
<!doctype html>
<html lang="pl">
<?php
include_once('/php/includes/utils.php');
include_once('/php/includes/head.php');


?>

<body>
    <nav class="navbar navbar-dark color1-bg">
        <div class="container-fluid">
            <a href="/">
                <h1 class="color4">OnionVille</h1>
            </a>
            <?php if (isset($_SESSION['login'])) : ?>

                <div class="float-right text-center">
                    <h1> <?php echo $_SESSION['name'] ?> </h1>
                     <?php if($_SESSION['verify']){echo "<h2 class=\"color7\">Konto Aktywne</h2>";}else{echo "<h2 class=\"color6\">Oczekuje na weryfikacje</h2>";} ?> 
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
        <?php if (isset($_SESSION['login']) && $_SESSION['verify'] == 1) : ?>
            <div class="side-menu side-menu-left">
                <div class="p-4 mt-4 center color1-bg rounded-1">
                    <h1>Menu</h1>
                </div>

                <div class="p-4 mt-4 center color1-bg rounded-1">
                    <a href="/php/requestitem.php" class="buttonStyle">Zamów</a>
                    <?php echo printMenuButtons();?>
                </div>
            </div>
            <div class="side-menu side-menu-right">
                <div class="p-4 mt-4 center color1-bg rounded-1">
                    <h1>Zamówienia</h1>
                </div>
                <div class="p-4 mt-4 center color1-bg rounded-1">
                    <a href="/php/board.php" class="buttonStyle">Tablica</a>
                    <a href="/php/userrequest.php" class="buttonStyle">Moje Zamówienia</a>
                    <a href="/php/userjobs.php" class="buttonStyle">Moje Zadania</a>
                    <a href="/php/requestlist.php" class="buttonStyle">Lista Zamówień</a>
                </div>
            </div>
        <?php endif; ?>

    </div>
    <?php
    if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
        include('/php/includes/select-jumbo.php');
    } else {
        include('/php/includes/login-jumbo.php');
    }
    ?>
    

</body>

</html