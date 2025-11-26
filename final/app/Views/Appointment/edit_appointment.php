<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
</head>
<body>
    <h1>Edit Appointment</h1>

     <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

     <?php session()->set('activity', 'edit') ?>

    <form action="<?= base_url('appointment/update/'.$a['appointment_id']) ?>" method="post">
        <?= csrf_field() ?>
        
        <label for="">Appointment Date:</label>
        <input type="datetime-local" name="appointment_date" id="" min="<?= date('Y-m-d\TH:i') ?>" value="<?= esc($a['appointment_date']) ?>" required><br><br>
        <label for="">Purpose:</label>
        <input type="text" name="purpose" id="" value="<?= esc($a['purpose']) ?>" required><br><br>
        <input type="submit" value="Update Appointment">
    </form>
</body>
</html>