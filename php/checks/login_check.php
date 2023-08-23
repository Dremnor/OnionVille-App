<?php
    updateSessionData($_SESSION['id'], $_SESSION);
    if (!isset($_SESSION['login'])){
        header("Location: /php/error/perm-error.php");
        die();
    }