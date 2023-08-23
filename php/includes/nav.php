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
            <div class="">
                <h1></h1>
            </div>
        <?php endif; ?>
    </div>

</nav>