<?php
require_once __DIR__ . '/../config.php';
function getAuthors() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "SELECT * FROM authors";
    $result = $conn->query($sql);
    $authors = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $authors[] = $row;
        }
    }
    $conn->close();
    return $authors;
}
?>
<?php   
function addAuthor($name , $bio) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $stmt = $conn->prepare("INSERT INTO authors (name, bio) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $bio);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    return $id;
}
?>

<?php
function getAuthorById($id) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $stmt = $conn->prepare("SELECT * FROM authors WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $author = null;
    
    if ($result->num_rows > 0) {
        $author = $result->fetch_assoc();
    }
    $stmt->close();
    $conn->close();
    return $author;
}
?>

<?php
function getAuthorsBooks($author_id) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $stmt = $conn->prepare("SELECT * FROM books WHERE author_id = ?");
    $stmt->bind_param("i", $author_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    $stmt->close();
    $conn->close();
    return $books;
}
?>
<?php 
function getAllAuthors() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "SELECT * FROM authors";
    $result = $conn->query($sql);
    $authors = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $authors[] = $row;
        }
    }
    $conn->close();
    return $authors;
}   