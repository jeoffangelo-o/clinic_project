<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of User</title>
</head>
<body>

    <?php if(session()->get('role') === 'admin'): ?>
        <h1>List of User</h1>

        <?php if(session()->getFlashData('message')): ?>
           <p> <?= session()->getFlashData('message') ?></p>
        <?php endif; ?>

        <table border='1'>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>

            <?php foreach($user as $u): ?>

                <tr>
                    <td><?= $u['user_id'] ?></td>
                    <td><?= $u['username'] ?></td>
                    <td><?= $u['email'] ?></td>
                    <td><?= $u['role'] ?></td>
                    <td><?= $u['created_at'] ?></td>

                    <td>
                        <a href="<?= base_url('/edit_user/'.$u['user_id']) ?>">Edit</a>
                        <a href="<?= base_url('/delete_user/'.$u['user_id']) ?>" onclick="return confirm('Are you sure to delete USER ID # <?= $u['user_id']  ?> (<?=  $u['username'] ?>)')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
             
        </table>

    <?php else: ?>
        
    <?php endif; ?>
</body>
</html>