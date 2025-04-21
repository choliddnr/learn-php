<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Index View</title>
</head>

<body>
    <header>
        <h1>Welcome to My PHP App</h1>
    </header>
    <main>
        <p>This is the index view of your PHP application.</p>
        <?php
        // Example PHP code
        echo "<p>Today's date is: " . date('Y-m-d') . "</p>";
        ?>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> My PHP App. All rights reserved.</p>
    </footer>
</body>

</html>