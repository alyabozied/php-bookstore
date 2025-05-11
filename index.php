<?php
$page = $_GET['page'] ?? 'home';
if ($page === ''){
    $file = "home.php";
}
$file = "pages/$page";
if (file_exists($file .".php")) {
    include $file.".php";
} else if(file_exists($file."/index.php")) {
    include $file . "/index.php";
} else {
    include "pages/404.php";
}