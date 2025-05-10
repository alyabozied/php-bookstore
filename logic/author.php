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