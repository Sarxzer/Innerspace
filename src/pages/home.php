<?php

/**
 * @var string $includesDir
 * @var string $cssDir
 * @var string $jsDir
 */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innerspace</title>
    <link rel="stylesheet" href="<?= $cssDir ?>">
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/png">
    <script src="<?= $jsDir ?>" defer></script>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#your-accent-color">
</head>

<body>
    <div class="page">
        <div class="pixel-scanlines"></div>
        <div class="content">
            <?php include $includesDir . '/navbar.php'; ?>

            <div class="main">

                <h1>Innerspace</h1>

                <p>Welcome to Innerspace! This is a platform for managing and sharing information about systems, members, and fronting sessions.</p>

                <h2>Getting Started</h2>

                <p>To get started, please log in or create an account. Once you have an account, you can create your own system, add members, and track fronting sessions.</p>

                Lorem ipsum dolor sit amet consectetur adipisicing elit. Id quos esse ullam. Labore velit molestiae perferendis voluptate ipsam, accusamus mollitia sed reiciendis quas, eius eum tempora minima. Qui, temporibus molestiae?
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus nulla, nam minus consequatur, hic illo molestias laborum magni odio consequuntur tempore quam repellat repudiandae non et id pariatur a necessitatibus.
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus accusamus assumenda sit quos consequatur illum laudantium porro ipsam quidem nisi. Perspiciatis distinctio quae aperiam. Veritatis autem eligendi voluptates ad saepe.
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste molestias iure facilis dicta ad modi cupiditate totam dolorem libero explicabo consequuntur neque voluptatibus inventore numquam, fugit hic unde in sed?
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolores autem dolor ratione placeat distinctio voluptatibus explicabo eligendi quo quis error itaque, impedit reprehenderit aspernatur exercitationem unde nemo dignissimos! Id, recusandae.

            </div>
            <?php include $includesDir . '/footer.php'; ?>
        </div>
    </div>
    <!-- <h1>Innerspace</h1>
    <p>Welcome to Innerspace! This is a platform for managing and sharing information about systems, members, and fronting sessions.</p>

    <h2>Getting Started</h2>
    <p>To get started, please log in or create an account. Once you have an account, you can create your own system, add members, and track fronting sessions.</p> -->

</body>

</html>