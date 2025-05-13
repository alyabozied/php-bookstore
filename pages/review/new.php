<?php

include "logic/auth.php";
include_once "logic/review.php";
?>

<?php
session_start();
if (!checkLoginStatus()){
    
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=login");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'] ?? '';
    $rating = $_POST['rating'] ?? '';
    $bookId = $_POST['book_id'] ?? '';
    $username= $_SESSION['user']?? '';
    if (!empty($comment) && !empty($rating)) {
        $id = addReview($bookId , $username,$comment, $rating);
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=book/show&id=$bookId");
        exit;
    } else {
        echo "<p>All fields are required.</p>";
    }
}
?>