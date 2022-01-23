<!DOCTYPE html>
<html lang="ca">
<?php use App\Registry; ?>
<head>
    <meta charset="utf-8">
    <title>Tancar sessió</title>
    <meta name="description" content="PHP, PHPStorm">
    <meta name="author" content="Homer Simpson">
</head>

<body>
<h1>Tancar sessió</h1>
<?php if (!isPost()) : ?>
    <p>Segur que vols tancar la sessio?</p>
    <form action="<?= Registry::get(\App\Registry::ROUTER)->generate("loggout") ?>" method="post" enctype="multipart/form-data">
        <div>
            <input type="submit" name="response" value="Sí"/>
            <input type="submit" name="response" value="No"/>
        </div>
    </form>
<?php else: ?>
    <?php if (!empty($errors)): ?>
        <h2><?= array_shift($errors) ?></h2>
    <?php else: ?>
        <h2><?= $message ?></h2>
    <?php endif; ?>
<?php endif; ?>
</body>

</html>