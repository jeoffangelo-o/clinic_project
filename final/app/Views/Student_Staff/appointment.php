<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment</title>
</head>
<body>
    <h1>Appointment</h1>

    <?php if(!session()->get('hasPatient')): ?>
        <p>You don't have patient info. <a href="/patient/add">Add</a>  to Continue</p>
    <?php else: ?>
        
    <button><a href="/appointment/add">Create Appointment</a></button>



    <h3>My Appointments</h3>

    <?php if(!empty($appoint)): ?>

        <?php foreach($appoint as $a): ?>
            <div>
                <p><strong>Appointment ID:</strong> <?= $a['appointment_id'] ?></p>
                <p><strong>Patient ID:</strong> <?= $a['patient_id'] ?></p>
                <p><strong>Appointment Date:</strong> <?= $a['appointment_date'] ?></p>
                <p><strong>Purpose:</strong> <?= $a['purpose'] ?></p>
                <p><strong>Status:</strong> <?= $a['status'] ?></p>
                <p><strong>Remarks:</strong> <?= (!empty($a['remarks'])) ? $a['remarks'] : 'None'  ?></p>
                <p><strong>Date Created:</strong> <?= $a['created_at'] ?></p>
            </div>
        <?php endforeach; ?>

    <?php else: ?>

    <?php endif; ?>
    
    <?php endif; ?>
</body>
</html>