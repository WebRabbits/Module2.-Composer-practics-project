<?php

use function Tamtamchik\SimpleFlash\flash;

$this->layout("layout", ["title" => "Home"]);
?>


<?php if (!$isLoggedIn): ?>
    <?php echo flash()->display("error"); ?>
<?php else: ?>
    <h1>User Profile</h1>
    <p>Hello, <?= $this->e($name); ?></p>
    <p>Your email: <?= $this->e($email); ?></p>
    <?php echo flash()->display("error");?>
    <form action="" method="post">
        <input type="text" name="oldPassword" class="mb-2" placeholder="Input old password"><br>
        <input type="text" name="newPassword" class="mb-2" placeholder="Input new password"><br>

        <button type="submit" class="btn btn-info">Change password</button>
    </form>
<?php endif; ?>