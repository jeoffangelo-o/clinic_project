<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User ID</title>
</head>
<body>
    <h1>Edit User ID: <?= esc($user['user_id']) ?> </h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('/update_user/'.$user['user_id']) ?>" method="post">
        <?= csrf_field() ?>

        <label for="">Username:</label>
        <input type="text" name="username" id="" value="<?= esc($user['username']) ?>"   required><br><br>
        <label for="">Old Password:</label>
        <input type="password" name="password" id=""><br><br>
        <label for="">New Password:</label>
        <input type="password" name="newpassword" id=""><br><br>
        <label for="">E-Mail:</label>
        <input type="email" name="email" id="" value="<?= esc($user['email']) ?>" required><br><br>

        <?php if(session()->get('role')==='admin'): ?>
            <label for="">Role(<?= esc($user['role']) ?>): </label>
            <input type="text" name="role" id="" placeholder="'admin', 'nurse', 'student', 'staff'" required><br><br>

        <?php else: ?>
            <input type="hidden" name="role" value="<?= esc($user['role']) ?>">
        <?php endif; ?>

        
        
        
       
        <input type="submit" value="Edit">
    </form>

    
</body>
</html>