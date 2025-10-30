<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <form action="/store_user" method="post">
        <label for="">Username:</label>
        <input type="text" name="username" id="" required><br><br>
        <label for="">Password:</label>
        <input type="text" name="password" id="" required><br><br>
        <label for="">E-Mail:</label>
        <input type="email" name="email" id="" required><br><br>
        <label for="">Role:</label>
        <input type="text" name="role" id="" placeholder="'admin', 'nurse', 'student', 'staff'" required><br><br>
        <input type="submit" value="Register">
    </form>

    
</body>
</html>