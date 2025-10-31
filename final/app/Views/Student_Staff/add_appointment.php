<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Appointment</title>
</head>
<body>
    <h1>Add Appointment</h1>

     <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <form action="/appointment/store" method="post">
        <label for="">Appointment Date:</label>
        <input type="datetime-local" name="appointment_date" id="" min="<?= date('Y-m-d\TH:i') ?>" required><br><br>
        <label for="">Purpose:</label>
        <input type="text" name="purpose" id="" required><br><br>
        <input type="submit" value="Add Appointment">
    </form>
</body>
</html>