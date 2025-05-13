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
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();
    $conn->close();
    return $id;
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
function searchBooks($searchTerm, $page = 1, $pageSize = 10) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $offset = ($page - 1) * $pageSize;
    $sql = "SELECT books.*, authors.name AS author_name FROM books 
            JOIN authors ON books.author_id = authors.id 
            WHERE books.title LIKE ? OR authors.name LIKE ?
            ORDER BY average_rating DESC, review_count DESC
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $searchTermLike = "%$searchTerm%";
    $stmt->bind_param("ssii", $searchTermLike, $searchTermLike, $pageSize, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }

    // Optionally, get total count for pagination
    $countSql = "SELECT COUNT(*) as total FROM books 
                 JOIN authors ON books.author_id = authors.id 
                 WHERE books.title LIKE ? OR authors.name LIKE ?";
    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("ss", $searchTermLike, $searchTermLike);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $total = $countResult->fetch_assoc()['total'];
    $countStmt->close();

    $stmt->close();
    $conn->close();

    return [
        'books' => $books,
        'total' => $total,
        'page' => $page,
        'pageSize' => $pageSize
    ];
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
function getBooksByGenre($genre, $page = 1, $pageSize = 10) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $offset = ($page - 1) * $pageSize;
    $sql = "SELECT books.*, authors.name AS author_name FROM books 
            JOIN authors ON books.author_id = authors.id 
            WHERE books.genre = ?
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $genre, $pageSize, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $books = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }

    // Get total count for pagination
    $countSql = "SELECT COUNT(*) as total FROM books 
                 JOIN authors ON books.author_id = authors.id 
                 WHERE books.genre = ?";
    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("s", $genre);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $total = $countResult->fetch_assoc()['total'];
    $countStmt->close();

    $stmt->close();
    $conn->close();
    $result->close();

    return [
        'books' => $books,
        'total' => $total,
        'page' => $page,
        'pageSize' => $pageSize
    ];
}
?>


<?php
function getBooksGroupedByGenre() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "SELECT books.*, authors.name AS author_name, books.genre 
            FROM books 
            JOIN authors ON books.author_id = authors.id
            ORDER BY average_rating DESC, review_count DESC";
    $result = $conn->query($sql);
    $groupedBooks = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $genre = $row['genre'];
            if (!isset($groupedBooks[$genre])) {
                $groupedBooks[$genre] = [];
            }
            $groupedBooks[$genre][] = $row;
        }
    }
    $conn->close();
    $result->close();

    // Sort genres by number of books (descending)
    uasort($groupedBooks, function($a, $b) {
        return count($b) - count($a);
    });

    return $groupedBooks;
}
?>