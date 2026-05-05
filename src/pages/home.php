<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innerspace</title>
</head>

<body>
    <h1>Innerspace</h1>
    <p>Welcome to Innerspace! This is a platform for managing and sharing information about systems, members, and fronting sessions.</p>

    <h2>Getting Started</h2>
    <p>To get started, please log in or create an account. Once you have an account, you can create your own system, add members, and track fronting sessions.</p>

    <h3>Test image upload</h3>
    <form method="POST" action="/upload-pfp.php" enctype="multipart/form-data">
        <input type="file" name="pfp" accept="image/*" required>
        <button>Upload</button>
    </form>

    <?php
    // list all uploaded pfps for testing (/uploads/pfps/{user_id}/{user_id}.webp)
    $pfpDir = __DIR__ . "/../uploads/pfps";
    if (is_dir($pfpDir)) {
        $users = scandir($pfpDir);
        foreach ($users as $userId) {
            if ($userId === '.' || $userId === '..') continue;
            $pfpPath = "$pfpDir/$userId/$userId.webp";
            if (file_exists($pfpPath)) {
                echo "<p>User $userId: <img src=\"/uploads/pfps/$userId/$userId.webp\" alt=\"PFP\" width=\"64\"></p>";
            }
        }
    }
    ?>
</body>

</html>