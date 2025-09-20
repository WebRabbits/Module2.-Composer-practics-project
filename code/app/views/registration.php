<?php

use function Tamtamchik\SimpleFlash\flash;

$this->layout("layout", ["title" => "Registration"]);
?>

<h2>Форма регистрации</h2>
<?php
if (!$anException) {
    echo flash()->display("info");
    echo flash()->display("success");
} else {
    echo flash()->display("error");
}
?>
<div class="container">
    <form action="" method="post">
        <label for="email">Email</label>
        <input type="text" name="email" class="mb-2"><br>
        <label for="password">Password</label>
        <input type="text" name="password" class="mb-2"><br>
        <label for="username">Username</label>
        <input type="text" name="username" class="mb-2"><br>

        <button type="submit" class="btn btn-info">Sign Up</button>
    </form>
</div>