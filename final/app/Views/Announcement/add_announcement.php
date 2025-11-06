<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Announcement</title>
</head>
<body>
    <h1>Create Announcement</h1>

    <form action="/announcement/add" method="post">
        <label for="">Image Url:</label>
        <input type="text" name="url" id="">
        <label for="">Title:</label>
        <input type="text" name="title" id=""><br><br>
        <label for="">Content:</label>
        <textarea name="content" id=""></textarea><br><br>
        <label for="">Posted Until:</label>
        <input type="datetime-local" name="posted_until" id="">
        <input type="submit" value="Create Announcement">

    </form>
</body>
</html>