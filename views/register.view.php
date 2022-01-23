<!DOCTYPE html>
<html lang="ca">
<?php use App\Registry; ?>
<head>
    <meta charset="utf-8">
    <title>Registrar un usuari</title>
    <meta name="description" content="PHP, PHPStorm">
    <meta name="author" content="Homer Simpson">
</head>

<body>
<header>
    <nav>
        <ul><li><a href="<?= Registry::get(\App\Registry::ROUTER)->generate("movie_list") ?>">Inici</a>
            </li>
        </ul>
    </nav>
</header>
<div class="container">
    <h1>Registrar un usuari</h1>
    <?php if (!empty($errors)): ?>
        <div>
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="<?= Registry::get(\App\Registry::ROUTER)->generate("register") ?>" method="post" enctype="multipart/form-data" novalidate>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter username" value="<?= $data["username"] ?>">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <div class="form-group">
            <label for="passwordc">Password</label>
            <input type="password" name="passwordc" class="form-control" placeholder="Password copy">
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>

</body>

</html>