<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
</head>
<body>
    <h1>Edit Patient</h1>

      <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('/patient/update/'.$p) ?>" method="post">
        <label for="user_id">User ID: </label>
        <input type="number" name="user_id" id="" placeholder="leave blank if none" value="<?= $p['user_id']?>"><br><br>
        <label for="user_id">First Name:</label>
        <input type="text" name="first_name" id="" value="<?= $p['first_name']?>" required><br><br>
        <label for="user_id">Middle Name:</label>
        <input type="text" name="middle_name" id="" value="<?= $p['middle_name']?>"><br><br>
        <label for="user_id">Last Name:</label>
        <input type="text" name="last_name" id="" value="<?= $p['last_name']?>" required><br><br>
        <label for="">Gender:</label>
        <select name="gender" id="">
            <option value="male" <?= ($p['gender'] === 'male') ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= ($p['gender'] === 'female') ? 'selected' : '' ?> >Female</option>
            <option value="other" <?= ($p['gender'] === 'other') ? 'selected' : '' ?> >Other</option>
        </select><br><br>
        <label for="">Birth Date:</label>
        <input type="date" name="birth_date" id="" value="<?= $p['birth_date']?>" required><br><br>
        <label for="">Phone Number:</label>
        <input type="text" name="contact_no" value="<?= $p['contact_no']?>" required><br><br>
        <label for="">Address:</label>
        <input type="text" name="address" value="<?= $p['address']?>" required><br><br>
        <label for="">Blood Type:</label>
        <select name="blood_type" id="" required>
            <option value="">--Select--</option>
            <option value="A+" <?= ($p['blood_type'] === 'A+') ? 'selected' : '' ?> >A+</option>
            <option value="A-" <?= ($p['blood_type'] === 'A-') ? 'selected' : '' ?> >A-</option>
            <option value="B+" <?= ($p['blood_type'] === 'B+') ? 'selected' : '' ?> >B+</option>
            <option value="B-" <?= ($p['blood_type'] === 'B-') ? 'selected' : '' ?> >B-</option>
            <option value="AB+" <?= ($p['blood_type'] === 'AB+') ? 'selected' : '' ?> >AB+</option>
            <option value="AB-"<?= ($p['blood_type'] === 'AB-') ? 'selected' : '' ?> >AB-</option>
            <option value="O+" <?= ($p['blood_type'] === 'O+') ? 'selected' : '' ?> >O+</option>
            <option value="O-" <?= ($p['blood_type'] === 'O-') ? 'selected' : '' ?> >O-</option>
        </select><br><br>
        <label for="">Allergies:</label>
        <input type="text" name="allergies" value="<?= $p['allergies']?>" ><br><br>
        <label for="">Medical History:</label>
        <input type="text" name="medical_history" value="<?= $p['medical_history']?>" ><br><br>
        <label for="">Emergency Contact Number:</label>
        <input type="text" name="emergency_contact" value="<?= $p['emergency_contact']?>" ><br><br>
        <input type="submit" value="Update Patient">
        
</body>
</html>