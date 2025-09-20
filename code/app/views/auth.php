<?php

use function Tamtamchik\SimpleFlash\flash;

$this->layout("layout", ["title" => "Login"]);
?>

<h2>Форма авторизации</h2>
<?php 
if($anException) {
    echo flash()->display("error");
}
?>
<div class="container">
    <form action="" method="post">
        <label for="email">Email</label>
        <input type="text" name="email" class="mb-2"><br>
        <label for="password">Password</label>
        <input type="text" name="password"class="mb-2"><br>

        <button type="submit" class="btn btn-info">Sig In</button>
    </form>
</div>