<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Consultation</title>
</head>
<body>
    <h1>Add Consultation</h1>

    <?php if(session()->get('role') === 'admin' || session()->get('role')):  ?>

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
                
                <label for="">Patient ID:</label>
                <input type="number" name="patient_id" id="">

            <?php else: ?>
                
                <label for="">Appointment ID:</label>
                <input type="number" name="appointment_id" id="">
                <input type="hidden" name="patient_id" id="">

            <?php endif; ?>

        </form>

    <?php else: ?>

    <?php endif; ?>

</body>
</html>