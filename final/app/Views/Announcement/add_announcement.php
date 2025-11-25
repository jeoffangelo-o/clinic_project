<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement</title>
</head>
<body>
    <h1>Create Announcement</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?=  session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <form action="/announcement/store" method="post">
        <?= csrf_field() ?>
        <label for="">Image Url:</label>
        <input type="url" name="url" id=""><br><br>
        <label for="">Title:</label>
        <input type="text" name="title" id=""><br><br>
        <label for="">Content:</label><br>
        <textarea name="content" id=""></textarea><br><br>
        <label for="">Posted Until:</label>
        <input type="datetime-local" name="posted_until" id=""><br><br>
        <input type="submit" value="Create Announcement">

    </form>
</body>
</html>