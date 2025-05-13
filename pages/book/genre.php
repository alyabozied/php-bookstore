<?php
include "logic/book.php";
include "logic/author.php";
$genre = isset($_GET['genre']) ? $_GET['genre'] : null;
$pageNumber = isset($_GET['pageNumber']) ? $_GET['pageNumber'] : 1;
$pageSize = isset($_GET['pageSize']) ? intval($_GET['pageSize']) : 10;
$result = getBooksByGenre($genre, $pageNumber, $pageSize);

$title = "Books in $genre";
ob_start();
?>
<link rel="stylesheet" type="text/css" href="assets/css/carousel.css">
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-semibold text-blue-700 text-center mb-6">Books in <?= htmlspecialchars($genre)?></h1>

    <div id="searchResults" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
                      
        <?php foreach ($result['books'] as $book): ?>
            <?php
                if (empty($book['cover_image'])) {
                    $book['cover_image'] = 'assets/images/default_cover.jpg'; // Default image if none is provided
                }
            ?>
            <div class="book-card">
                <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                <h2 class="text-xl font-semibold text-blue-700 mb-2"><?= htmlspecialchars($book['title']) ?></h2>
                <p class="text-gray-600 mb-2">By <?= htmlspecialchars($book['author_name']) ?></p>
                <p class="description text-gray-700 mb-4"><?= htmlspecialchars($book['description']) ?></p>
                <a href="index.php?page=book/show&id=<?= $book['id'] ?>" class="text-blue-500 hover:text-blue-700">View Details</a>
            </div>
        <?php endforeach; ?>
        <?php if (empty($result['books'])): ?>
            <div class="col-span-full text-center text-gray-500">
                No books found.
            </div>
        <?php endif; ?>

        <?php if ($result['total'] > 1): ?>
            <div class="col-span-full flex justify-center mt-6">
                <div class="inline-flex space-x-2">
                    <a href="?page=book/genre&genre=<?= urlencode($genre) ?>&pageNumber=1&pageSize=<?= $pageSize ?>"
                            class="px-3 py-1 rounded <?= $pageNumber == 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-blue-100' ?>">
                             first
                        </a>
                    
                    <?php for ($i = max( 2, $pageNumber - 3); $i <= min($result['total'] / $pageSize ,$pageNumber+3); $i++): ?>
                        <a href="?page=book/genre&genre=<?= urlencode($genre) ?>&pageNumber=<?= $i ?>&pageSize=<?= $pageSize ?>"
                        class="px-3 py-1 rounded <?= $i == $pageNumber ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-blue-100' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>
                    <a href="?page=book/genre&genre=<?= urlencode($genre) ?>&pageNumber=<?= ceil($result['total'] / $pageSize) ?>&pageSize=<?= $pageSize ?>"
                        class="px-3 py-1 rounded <?= $pageNumber == ceil($result['total'] / $pageSize) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-blue-100' ?>">
                         Last
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="assets/css/search.css">

<?php
$content = ob_get_clean();

include "layout/layout.php";