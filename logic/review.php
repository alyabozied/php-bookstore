<?php 
function getBookReviews($book_id,$page = 1, $pageSize = 10) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $offset = ($page - 1) * $pageSize;
    $sql = "SELECT reviews.*,users.username
            FROM reviews
            JOIN books ON reviews.book_id = books.id 
            join users ON reviews.user_id = users.id
            WHERE reviews.book_id = ?
            LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $book_id, $pageSize, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $reviews = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
    }

    // Get total count for pagination
    $countSql = "SELECT COUNT(*) as total FROM reviews
                 JOIN books ON reviews.book_id = books.id 
                 WHERE books.id = ?";
    $countStmt = $conn->prepare($countSql);
    $countStmt->bind_param("i", $book_id);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $total = $countResult->fetch_assoc()['total'];
    $countStmt->close();

    $stmt->close();
    $conn->close();
    $result->close();

    return [
        'reviews' => $reviews,
        'total' => $total,
        'page' => $page,
        'pageSize' => $pageSize
    ];
}
?>
<?php   
function addReview($book_id, $user_name, $review_text, $rating) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $user_id = $stmt->get_result()->fetch_assoc()['id'];
    $stmt = $conn->prepare("INSERT INTO reviews (book_id, user_id, comment, rating) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iisi", $book_id, $user_id, $review_text, $rating);
    $stmt->execute();
    $stmt = $conn->prepare("UPDATE books SET 
    review_count = (SELECT Count(rating) FROM reviews WHERE book_id = ?),
    average_rating = ( SELECT AVG(rating) FROM reviews WHERE book_id = ?)
    WHERE id = ?");
    $stmt->bind_param("iii", $book_id , $book_id, $book_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}