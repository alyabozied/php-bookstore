<?php 
include "logic/auth.php";
if (checkLoginStatus()) {
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=home");
    exit;
}
?>

<?php
$title = "Signup";
ob_start(); 
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"]; // Hash the password
    $out = register($username, $password);
    if ($out['status']) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=home");
        exit;
    } else {
        echo "<p style='color:red;'> " . $out['message'] . "</p>";
    }
}
?>

<form method="POST" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Register</button>
    <a href="index.php?page=login">login</a>
</form>

<link rel="stylesheet" type="text/css" href="assets/css/form.css">

<?php
$content = ob_get_clean();
include "layout/layout.php";
?>