<?php
$title = "404 Not Found";

ob_start(); 
?>

<div class="container">
    <h1>404</h1>
    <p>Oops! The page you are looking for does not exist.</p>
    <a href="index.php?page=home">Go Back to Home</a>
</div>

<?php
$content = ob_get_clean();
include "layout/layout.php";
?>