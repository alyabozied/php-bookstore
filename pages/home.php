<link rel="stylesheet" href="assets/css/home.css">
<?php
include "logic/auth.php";
$isUserSignedIn = checkLoginStatus();
?>

<?php
$title = "Home";
ob_start();
?>
<h2>Welcome to the Home Page</h2>
<?php if ($isUserSignedIn): ?>
    <p>Hi, <?= htmlspecialchars($_SESSION['user']) ?>!</p>
<?php else: ?>
    you are not logged in.
    <p>To access the full features of this site, please log in or register.</p>
    <a class="login" href="index.php?page=login">Login</a>  <a class="register" href="index.php?page=signup">Register</a>
<?php endif; ?>
<?php
$content = ob_get_clean();
include "layout/layout.php";
?>
