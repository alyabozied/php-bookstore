<?php
include "logic/auth.php";
if (checkLoginStatus()) {
    logout();
}

header("Location: " . $_SERVER['PHP_SELF'] . "?page=login");
exit;