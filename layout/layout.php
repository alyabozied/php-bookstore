<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Default Title' ?></title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@latest"></script>
</head>
<body>
    <header>
        <nav>
            <div class="left">
                <a href="index.php?page=home">Home</a> |
                <a href="index.php?page=about">About</a> |
                <a href="index.php?page=book/showgenres">Genres</a>
            </div>

            <div class="right">
                
                <form action="index.php" method="get" style="display:inline;">
                    <input type="hidden" name="page" value="book/search">
                    <input type="text" name="q" placeholder="Search books..." required>
                    <button type="submit">Search</button>
                </form>

                <?php if (isset($_SESSION['user'])): ?>
                 <a href="index.php?page=logout">Logout</a>
                <?php else: ?>      
                    <a href="index.php?page=login">Login</a>
                    | <a href="index.php?page=signup">Signup</a>
                    <?php endif; ?>
                </nav>
            </div>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> My Site</p>
    </footer>
</body>
</html>
