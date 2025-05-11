<?php
$title = "New Book";
include_once "logic/book.php";
include_once "logic/author.php";
include_once "logic/auth.php";
ob_start();
$authors = getAllAuthors();
?>

<?php
if (!checkLoginStatus()){
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=login");
    exit;
}
if(getUserType($_SESSION['user']) != 'admin' && getUserType($_SESSION['user']) != 'vendor'){
    header("Location: " . $_SERVER['PHP_SELF'] . "?page=home");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $authorId = $_POST['author'] ?? '';
    $publishedDate = $_POST['publishDate'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $description = $_POST['description'] ?? '';
    $coverImageUrl = $_POST['coverImageUrl'] ?? '';

    if (!empty($title) && !empty($authorId) && !empty($genre) && !empty($publishedDate) && !empty($description) && !empty($coverImageUrl)) {
        $id = addBook($title, $authorId,$publishedDate, $genre,$description ,$coverImageUrl);
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=book/show&id=$id");
        exit;
    } else {
        echo "<p>All fields are required.</p>";
    }
}
?>
 <div class="container mx-auto p-8">
        <h1 class="text-3xl font-semibold text-blue-600 text-center mb-6">Add New Book</h1>
        <form method="POST" action="" id="addBookForm" class="bg-white shadow-md rounded-lg p-8">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="author" class="block text-gray-700 text-sm font-bold mb-2">Author:</label>
                <select id="author" name="author" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="" disabled selected>Select an author</option>
                    <?php foreach ($authors as $author): ?>
                        <option value="<?php echo htmlspecialchars($author['id']); ?>">
                            <?php echo htmlspecialchars($author['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="genre" class="block text-gray-700 text-sm font-bold mb-2">Genre:</label>
                <input type="text" id="genre" name="genre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="publishDate" class="block text-gray-700 text-sm font-bold mb-2">Publish Date:</label>
                <input type="date" id="publishDate" name="publishDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32 resize-y" required></textarea>
            </div>

            <div class="mb-4">
                <label for="coverImageUrl" class="block text-gray-700 text-sm font-bold mb-2">Cover Image URL:</label>
                <input type="url" id="coverImageUrl" name="coverImageUrl" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Add Book
                </button>
                <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="reset">
                    Cancel
                </button>
            </div>
        </form>
    </div>
<?php
$content = ob_get_clean();

include "layout/layout.php";
