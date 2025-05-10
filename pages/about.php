<?php
$title = "About";
ob_start();
?>

<h2>About Us</h2>
<p>Welcome to our bookstore! We are passionate about connecting readers with the stories they love. Our collection features a wide range of genres, from timeless classics to the latest bestsellers. Whether you're a casual reader or a literary enthusiast, we have something for everyone. Thank you for supporting our mission to spread the joy of reading!</p>
<h2>Our Mission</h2>
<p>At our bookstore, we believe in the power of stories to inspire, educate, and entertain. Our mission is to provide a welcoming space for readers of all ages to discover new books, share their love of reading, and connect with fellow book lovers. We strive to create a community where everyone feels valued and included.</p>
<?php
$content = ob_get_clean();

include "layout/layout.php";
