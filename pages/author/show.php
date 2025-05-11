<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : null;
if ($id === null) {
    header("Location: index.php?page=home");
    exit;
}
include "logic/author.php";
$author = getAuthorById($id);
if ($author === null) {
    header("Location: index.php?page=404");
    exit;
}
$authorBooks = getAuthorsBooks($id);

$title = "Author Details";
ob_start();
?>

<body class="bg-gray-100 font-inter leading-normal tracking-normal">
    <div class="container mx-auto p-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-semibold text-blue-700 mb-4" id="authorName"><?= htmlspecialchars($author['name'], ENT_QUOTES, 'UTF-8') ?></h1>
            <p class="text-gray-700 mb-4" id="authorBio"><?= htmlspecialchars($author['bio'],ENT_QUOTES, 'UTF-8')?></p>
            <?php if ($authorBooks): ?>
                <h2 class="text-xl font-semibold text-blue-700 mt-6 mb-3">Books by Author:</h2>
                <ul id="bookList" class="list-disc list-inside">
                    <?php foreach ($authorBooks as $book): ?>
                        <li class="mb-2">
                            <a href="index.php?page=book/show&id=<?= htmlspecialchars($book['id'], ENT_QUOTES, 'UTF-8') ?>" class="text-blue-500 hover:underline">
                                <?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-700">No books found for this author.</p>
            <?php endif; ?>
        </div>
    </div>

<?php
$content = ob_get_clean();

include "layout/layout.php";