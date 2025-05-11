<?php
$title = "New Author";
include_once "logic/author.php";
include_once "logic/auth.php";
ob_start();
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
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    if (empty($name) || empty($bio)) {
        echo "<p style='color:red;'>All fields are required.</p>";
    } else {
        $id = addAuthor($name, $bio);
        header("Location: " . $_SERVER['PHP_SELF'] . "?page=author/show&id=$id");
        exit;
    }
}
?>
 <div class="container mx-auto p-8">
        <h1 class="text-3xl font-semibold text-blue-600 text-center mb-6">Add New Author</h1>
        <form method="POST" action="" id="addBookForm" class="bg-white shadow-md rounded-lg p-8">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="bio" class="block text-gray-700 text-sm font-bold mb-2">Bio:</label>
                <textarea id="bio" name="bio" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32 resize-y" required></textarea>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    
                    Add Author
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
