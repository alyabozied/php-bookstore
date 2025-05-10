<?php 
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if ($id === null) {
    header("Location: index.php?page=home");
    exit;
}
echo "<h1>Book ID: $id</h1>";
