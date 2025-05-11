<?php
include "logic/book.php";
include "logic/author.php";
$booksByGenre = getBooksGroupedByGenre();

$title = "Book Details";
ob_start();
?>
<link rel="stylesheet" type="text/css" href="assets/css/carousel.css">
<div class="container mx-auto p-8">
    <h1 class="text-3xl font-semibold text-blue-700 text-center mb-6">Book Genres</h1>
    
    <?php
                // Get only the first 10 genres
            $limitedGenres = array_slice($booksByGenre, 0, 10, true);
            foreach ($limitedGenres as $genre => $books): ?>
                <div class="carousel">
                    <h2 class="text-2xl font-semibold text-blue-700 mb-4"><?= htmlspecialchars($genre) ?></h2>
                    <div class="slider">
                        <button class="carousel-button left-button scrolling-buttons" aria-label="Scroll Left">
                            &#8592;
                        </button>
                    
                        <?php
                            // Get only the first 10 books for this genre
                            $limitedBooks = array_slice($books, 0, 10);
                            foreach ($limitedBooks as $book): 
                                if (empty($book['cover_image'])) {
                                    $book['cover_image'] = 'assets/images/default_cover.jpg'; // Default image if none is provided
                                }
                                ?>
                                <div class="carousel-card">
                                        <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="w-full rounded-md shadow-md mb-2">
                                        <h3 class="text-lg font-semibold text-blue-600"><?= htmlspecialchars($book['title']) ?></h3>
                                        <p class="text-gray-600">By <?= htmlspecialchars($book['author_name']) ?></p>
                                        <p class="description text-sm text-gray-500"><?= htmlspecialchars($book['description']) ?></p>
                                        <a href="index.php?page=book/show&id=<?= $book['id'] ?>" class="text-blue-500 hover:text-blue-700">View Details</a>

                                </div>
                        <?php endforeach; ?>
                        

                        <button class="carousel-button right-button scrolling-buttons"  aria-label="Scroll Right">
                            &#8594;
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>  
            
    <script src="assets/js/carousel.js"></script>
<?php
$content = ob_get_clean();

include "layout/layout.php";