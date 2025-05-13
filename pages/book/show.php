<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if ($id === null) {
    header("Location: index.php?page=home");
    exit;
}
include "logic/book.php";
include "logic/author.php";
include "logic/review.php";
$book = getBookById($id);
if ($book === null) {
    header("Location: index.php?page=404");
    exit;
}
$author = getAuthorById($book['author_id']);
if ($author === null) {
    header("Location: index.php?page=404");
    exit;
}
if (empty($book['cover_image'])) {
    $book['cover_image'] = 'assets/images/default_cover.jpg'; // Default image if none is provided
}
$title = "Book Details";
$result = getBookReviews($id);
$reviews = $result['reviews'];
ob_start();
?>
<div class="container mx-auto p-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <div class="md:w-1/3">
                    
                    <img id="bookCover" src="<?= $book['cover_image'] ?>" alt="Book Cover" class="w-full rounded-md shadow-md">
                </div>
                <div class="md:w-2/3">
                    <h1 class="text-3xl font-semibold text-blue-700 mb-2" id="bookTitle"><?= $book['title'] ?></h1>
                    <p class="text-gray-600 mb-1">By 
                        <a href="index.php?page=author/show&id=<?= $author['id'] ?>" class="text-blue-600 hover:text-blue-800">
                            <span class="font-semibold text-blue-600" id="bookAuthor"><?= $author['name']?></span>

                        </a>
                    </p>
                    <div class="flex items-center mb-2">
                        <span class="text-yellow-500">
                            <?php for ($i = 0; $i < intval($book['average_rating']); $i++): ?>
                                ★
                            <?php endfor; ?>
                            <?php for ($i = intval($book['average_rating']); $i < 5; $i++): ?>
                                ☆
                            <?php endfor; ?>
                        </span>                        
                        <span class="text-gray-500 text-sm">(<?= $book['review_count'] ?> reviews)</span>
                    </div>
                    <p class="text-gray-700 mb-4" id="bookDescription"><?= $book['description'] ?></p>
                    <p class="text-gray-700 mb-2"><strong>Genre:</strong> <span id="bookGenre"><?= $book['genre'] ?></span></p>
                    <p class="text-gray-700"><strong>Publish Date:</strong> <span id="bookPublishDate"><?= $book['published_date'] ?></span></p>
                    <button id="addReviewButton" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors duration-200 mt-4">
                        Add Review
                    </button>
                </div>
            </div>
        </div>
        <!-- Add a review form -->
        <div class="mt-8" id="addReviewForm">
            <h2 class="text-xl font-semibold mb-4">Add a Review</h2>
            <form action="index.php" method="post" class="bg-white p-6 rounded shadow space-y-4">
                <input type="hidden" name="page" value="review/new">
                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                <div>
                    <label for="rating" class="block text-gray-700 font-medium mb-1">Rating</label>
                    <select id="rating" name="rating" required class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Select rating</option>
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <option value="<?= $i ?>"><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div>
                        <label for="comment" class="block text-gray-700 font-medium mb-1">Comment</label>
                        <textarea id="comment" name="comment" rows="4" required class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit Review</button>
            </form>
        </div>
            
        <!-- book reviews  -->
        <div class="mt-8">
            <h2 class="text-2xl font-semibold mb-4">Reviews</h2>
            <?php if (!empty($reviews)): ?>
                <ul class="space-y-4">
                    <?php foreach ($reviews as $review): ?>
                        <li class="bg-gray-100 p-4 rounded shadow">
                            <div class="flex items-center mb-2">
                                <span class="font-bold text-blue-700"><?= htmlspecialchars($review['username']) ?></span>
                                <span class="ml-4 text-yellow-500">
                                    <?php for ($i = 0; $i < intval($review['rating']); $i++): ?>
                                        ★
                                    <?php endfor; ?>
                                    <?php for ($i = intval($review['rating']); $i < 5; $i++): ?>
                                        ☆
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <p class="text-gray-700"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            <div class="text-xs text-gray-500 mt-2"><?= htmlspecialchars($review['created_at']) ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500">No reviews yet for this book.</p>
            <?php endif; ?>
        </div>
        
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addReviewButton = document.getElementById('addReviewButton');
        const addReviewForm = document.getElementById('addReviewForm');

        addReviewButton.addEventListener('click', function() {
            addReviewForm.classList.toggle('show');
        });
    });
</script>
<style>
    #addReviewForm {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0;
        pointer-events: none;
    }
    #addReviewForm.show {
        max-height: 800px;
        opacity: 1;
        pointer-events: auto;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s;
    }
</style>
<?php
$content = ob_get_clean();

include "layout/layout.php";