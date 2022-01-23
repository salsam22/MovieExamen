<div class="container">
<h1>Pel·lícules</h1>
<?php
use App\Registry;

if (!empty($message)) :?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong><?= $message ?></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>
<p><a href="<?= Registry::get(\App\Registry::ROUTER)->generate("movie_create") ?>">Nova pel·lícula</a></p>
<ul>
    <?php foreach ($movies as $movie): ?>
        <li><a href="<?= Registry::get(\App\Registry::ROUTER)->generate("movie_show", ["id" => $movie->getId()]) ?>"><?= $movie->getTitle() ?></a>
            <ul>
                <li>
                    <a href="<?= Registry::get(\App\Registry::ROUTER)->generate("movie_edit", ["id" => $movie->getId()]) ?>">Editar</a>
                <li><a href="<?= Registry::get(\App\Registry::ROUTER)->generate("movie_delete", ["id" => $movie->getId()]) ?>">Borrar</a>
            </ul>
        </li>
    <?php endforeach; ?>
</ul>


</div>