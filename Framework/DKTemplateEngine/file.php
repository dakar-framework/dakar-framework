<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>tdk template !</title>
</head>
<body>
    <header>

    </header>
    <h1> <?= $big_title ?> </h1>
    <h2> <?= $user->id ?> from the template !</h2>
    <p> <?= $name,$age ?> </p>
    <h2> #for() </h2>
    <div id="#"> #(my_func())</div>
    <p> <?= $love ?> </p>
    <small id="#"> #explode() </small>

</body>
</html>
