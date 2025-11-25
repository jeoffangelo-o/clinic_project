<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
</head>
<body>
    <h1>Log In</h1>

     <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <form action="/auth" method="post">
        <?= csrf_field() ?>
        <label for="">Username:</label>
        <input type="text" name="username" id=""><br><br>
        <label for="">Password:</label>
        <input type="password" name="password" id=""><br><br>
        <input type="submit" value="Log In">

    </form>

</body>
</html>