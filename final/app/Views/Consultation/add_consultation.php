<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Consultation</title>
</head>
<body>
    <h1>Add Consultation</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'):  ?>

        <form action="/consultation/add" method="get" >
            <label for="">Service:</label>
            <select name="service" id="" onchange="this.form.submit()">
                <option value="walkin" <?= (session()->get('service') === 'walkin') ? 'selected' : '' ?>>Walk-In</option>
                <option value="appoint" <?= (session()->get('service') === 'appoint') ? 'selected' : '' ?>>Appointment</option>
            </select>
            <br><br>
        </form>
        
        <form action="/consultation/store" method="post">

            <?php if(session()->get('service') === 'walkin'): ?>
                
                <input type="hidden" name="service" value="walkin">
                
                <label for="">Patient ID:</label>
                <input type="number" name="patient_id" id="" required>

            <?php else: ?>

                <input type="hidden" name="service" value="appoint">

                <label for="">Appointment ID:</label>
                <input type="number" name="appointment_id" id="" required>

            <?php endif; ?>
            
            <br><br>
            <input type="hidden" name="nurse_id" value="<?= session()->get('user_id')?>">

            <label for="">Diagnosis:</label>
            <input type="text" name="diagnosis" id="" required><br><br>
            <label for="">Treatment:</label>
            <input type="text" name="treatment" id="" required><br><br>
            <label for="">Prescription:</label>
            <input type="text" name="prescription" id="" required><br><br>
            <label for="">Notes:</label>
            <input type="text" name="notes" id="" ><br><br>
            
            <input type="submit" value="Submit Consultation">

        </form>

    <?php else: ?>

    <?php endif; ?>

</body>
</html>