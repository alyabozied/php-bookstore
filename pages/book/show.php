<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if ($id === null) {
    header("Location: index.php?page=home");
    exit;
}
include "logic/book.php";
include "logic/author.php";
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
                    <p class="text-gray-600 mb-4">By 
                        <a href="index.php?page=author/show&id=<?= $author['id'] ?>" class="text-blue-600 hover:text-blue-800">
                            <span class="font-semibold text-blue-600" id="bookAuthor"><?= $author['name']?></span>

                        </a>
                    </p>
                    <p class="text-gray-700 mb-4" id="bookDescription"><?= $book['description'] ?></p>
                    <p class="text-gray-700 mb-2"><strong>Genre:</strong> <span id="bookGenre"><?= $book['genre'] ?></span></p>
                    <p class="text-gray-700"><strong>Publish Date:</strong> <span id="bookPublishDate"><?= $book['published_date'] ?></span></p>
                </div>
            </div>
        </div>
    </div>
<?php
$content = ob_get_clean();

include "layout/layout.php";