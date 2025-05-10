<?php
require_once __DIR__ . '/../config.php';

// Function to get a book by its ID
function getBookById($bookId) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "SELECT books.*, authors.name AS author_name FROM books 
            JOIN authors ON books.author_id = authors.id 
            WHERE books.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $conn->close();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // Book not found
    }
}
?>

<?php 
function getAllBooks() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "SELECT books.*, authors.name AS author_name FROM books 
            JOIN authors ON books.author_id = authors.id";
    $result = $conn->query($sql);
    $books = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    $conn->close();
    $result->close();
    return $books;
}   
?>
<?php
function addBook($title, $authorId, $publishedDate, $genre, $description,$coverImageUrl) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "INSERT INTO books (title, author_id, published_date, genre, description,cover_image) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissss", $title, $authorId, $publishedDate, $genre, $description,$coverImageUrl);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}
?>
<?php   
function updateBook($bookId, $title, $authorId, $publishedDate, $genre, $description ,$coverImageUrl) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "UPDATE books SET title = ?, author_id = ?, published_date = ?, genre = ?, description = ? , coverImageUrl = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssi", $title, $authorId, $publishedDate, $genre, $description ,$coverImageUrl , $bookId);
    $result = $stmt->execute();
    $stmt->close();
    $conn->close(); 
    return $result; 
}?>

<?php 
function deleteBook($bookId) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    $result =  $stmt->execute();
    $stmt->close();
    $conn->close();
    return $result;
}?>
<?php 
function searchBooks($searchTerm) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "SELECT books.*, authors.name AS author_name FROM books 
            JOIN authors ON books.author_id = authors.id 
            WHERE books.title LIKE ? OR authors.name LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$searchTerm%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }

    return $books;
}
?>

<?php 
function getAllGenres() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "SELECT DISTINCT genre FROM books";
    $result = $conn->query($sql);
    $genres = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $genres[] = $row['genre'];
        }
    }
    $conn->close();
    $result->close();       
    return $genres;
}
?>
<?php
function getBooksByGenre($genre) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "SELECT books.*, authors.name AS author_name FROM books 
            JOIN authors ON books.author_id = authors.id 
            WHERE books.genre = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $genre);
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
    $result->close();
    return $books;
}
?>