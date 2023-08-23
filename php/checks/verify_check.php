<?php
    updateSessionData($_SESSION['id'], $_SESSION);
    if (!isset($_SESSION['verify']) && $_SESSION['verify'] == 1){
        header("Location: /php/error/perm-error.php");
        die();
    }