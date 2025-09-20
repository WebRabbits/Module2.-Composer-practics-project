<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($title);?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <nav>
        <ul>
            <li><a href="/registration">Registration</a></li>
            <li><a href="/auth">Login</a></li>
            <li><a href="/home">Home</a></li>
            <li><a href="/posts">Posts</a></li>
            <li><a href="/about">About</a></li>
            <li><a href="/contacts">Contacts</a></li>
            <li><a href="/logout">Logout</a></li>
        </ul>
    </nav>
    <?= $this->section("content");?>
</body>
</html>