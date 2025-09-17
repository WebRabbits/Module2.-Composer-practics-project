<?php 
$this->layout("layout", ["title"=>"Posts"]);
?>

<h2>List All Posts</h2>
<div class="container">
    <?php foreach($postsInView as $post):?>
        <div class="post">
            <span>• <?= $post->title?></span> ||| 
            <span><?= $post->date?></span>
        </div>
        <br>
    <?php endforeach;?>
</div>