<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to CodeIgniter 4!</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">

<body>


    <div class="heroe">

        <h1>Welcome <?= session()->get('username') ?></h1>

        <h2>Your role is  <?= session()->get('role') ?></h2>

        <?php if(session()->get('role') === 'admin'): ?>
            <h1><a href="/list_user">List of User</a></h1>
        
        <?php else: ?>
             <h1><a href="<?= base_url('/edit_user/'.session()->get('user_id')) ?>">Edit My Information</a></h1>
        <?php endif; ?>

        <button><a href="/logout">LOGOUT</a></button>
    </div>

    <div>
        <h2>Manage Patient</h2>
    </div>

</header>


</body>
</html>
