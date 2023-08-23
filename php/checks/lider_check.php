<?php
    updateSessionData($_SESSION['id'], $_SESSION);
    if (!(isset($_SESSION['Lider']) || isset($_SESSION['Admin']))){
        header("Location: /php/error/perm-error.php");
        die();
    }