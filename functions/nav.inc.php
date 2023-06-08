<footer>
    <a href="index.php">Index</a><br>
    <?php // session not set.
    if (!isset($_SESSION["email"])) {
        echo "<a href=\"signup.php\">Sign Up</a><br>";
        echo "<a href=\"login.php\">Log In</a><br>";
    } else { // session is set.
        echo "<a href=\"friendlist.php\">Friend List</a><br>";
        echo "<a href=\"friendadd.php\">Add Friends</a><br>";
        echo "<a href=\"logout.php\">Log Out</a><br>";
    }
    ?>
    <a href="about.php">About</a>
</footer>
</body>
</html>