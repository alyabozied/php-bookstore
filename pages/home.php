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
    <p> your user type is <?= htmlspecialchars($_SESSION['user_type']) ?>.</p>
<?php else: ?>
    you are not logged in.
    <p>To access the full features of this site, please log in or register.</p>
    <br>
    <a class="login" href="index.php?page=login">Login</a>  <a class="register" href="index.php?page=signup">Register</a>
<?php endif; ?>
<?php if($_SESSION['user_type'] == 'vendor' || $_SESSION['user_type'] == 'admin') : ?>
    <div class="mt-4 flex gap-4">
        <a href="index.php?page=book/new" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Add Book</a>
        <a href="index.php?page=author/new" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Add Author</a>
    </div>
<?php endif; ?>



<?php
$content = ob_get_clean();
include "layout/layout.php";
?>
