<?php
    updateSessionData($_SESSION['id'], $_SESSION);
    if (!(isset($_SESSION['Oficer']) || isset($_SESSION['Admin']) || isset($_SESSION['Lider']))){
        header("Location: /php/error/perm-error.php");
        die();
    }