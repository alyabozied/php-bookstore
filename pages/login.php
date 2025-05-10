<?php 
include "logic/auth.php";
if (checkLoginStatus()) {
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=home");
    exit;
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $out = login($username, $password);
    if ($out['status']) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=home");
        exit;
    } else {
        echo "<p style='color:red;'>". $out['message']."</p>";
    }   
}
?>
<?php
$title = "Login";
ob_start(); 
?>
<form method="POST" action="">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
    <a href="index.php?page=signup">signup</a>
</form>
<link rel="stylesheet" type="text/css" href="assets/css/form.css">
<?php
$content = ob_get_clean();
include "layout/layout.php";
?>