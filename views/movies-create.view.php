<!DOCTYPE html>
<html lang="ca">
<?php use App\Registry; ?>
<head>
    <meta charset="utf-8">
    <title>Nova pel·lícula</title>
    <link rel="stylesheet" href="assets/global.css" />

</head>

<body>
<main>
    <header>
        <nav>
            <ul><li><a href="<?= Registry::get(\App\Registry::ROUTER)->generate("movie_list") ?>">Inici</a>
                </li>
            </ul>
        </nav>
    </header>
<h1>New movie</h1>

    <?php if (!empty($message)) :?>

        <h3><?=$message?></h3>
    <?php endif ?>
    <?php if (!empty($errors)): ?>
        <div>
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach ?>
        </ul>
        </div>
    <?php endif; ?>

<form action="<?= Registry::get(\App\Registry::ROUTER)->generate("movie_store") ?>" method="post" enctype="multipart/form-data" novalidate>


    <input type="hidden" name="token" value="<?= $formToken ?>"/>

    <div>
        <label for="title">Title</label>
        <input id="title" type="text" name="title" value="<?= $data["title"] ?>">
    </div>
    <div>
        <label for="release-date">Release date (YYYY-mm-dd)</label>
        <input id="title" type="text" name="release_date" value="<?= $data["release_date"] ?>">
    </div>
    <div>
        <label for="overview">Overview</label>
        <textarea id="overview" name="overview"><?= $data["overview"] ?></textarea>
    </div>
    <div>
        <label for="rating">Rating</label>
        <input id="rating" name="rating" type="number" step="0.01" value="<?= $data["rating"] ?>">
    </div>
    <div>
        <p>Poster</p>
        <input type="file" name="poster"/>
    </div>
    <div>
        <input type="submit" value="Crear">
    </div>
</form>
</main>
</body>

</html>