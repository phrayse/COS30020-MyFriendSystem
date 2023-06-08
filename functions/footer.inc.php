<footer>
    <a href="index.php">Index</a><br>
    <?php
    if (!isset($_SESSION["email"])) {
        echo "<a href=\"signup.php\">Sign Up</a><br>
            <a href=\"login.php\">Log In</a><br>";
    } else {
        echo "<a href=\"friendlist.php\">Friend List</a><br>
            <a href=\"friendadd.php\">Add Friends</a><br>
            <a href=\"logout.php\">Log Out</a><br>";
    }
    ?>
    <a href="about.php">About</a>
</footer>
</body>
</html>