<?php

use function Tamtamchik\SimpleFlash\flash;

$this->layout("layout", ["title" => "About"]);
?>

<h1>About</h1>
<span><?= $this->e($page) ?></span><br>
<span><?= $this->e($name) ?></span>
<p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Commodi, tempore!</p>

<?= flash()->display(); ?>