<?php

use function Tamtamchik\SimpleFlash\flash;

$this->layout("layout", ["title" => "Posts"]);
?>

<h2>List All Posts</h2>
<?= flash()->display("success"); ?>
<div class="container">
    <?php foreach ($postsInView as $post): ?>
        <div class="post">
            <span>• <?= $post->title ?></span> |||
            <span><?= $post->date ?></span>
        </div>
        <br>
    <?php endforeach; ?>
    <?= $pagination ?>
</div>

<form action="" method="post">
    <button type="submit" class="btn btn-info mb-5">"Сгенерировать посты +30 штук"</button>
</form>