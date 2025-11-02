<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation</title>
</head>
<body>
    <h1>Consultation</h1>

    
    <button><a href="/consultation/add">Add Consultation</a></button>

    <?php if(!empty($consult)): ?>

        <?php foreach($consult as $c): ?>

            <div style="border: 1px solid black;">
                <p><strong>Appointment ID:</strong> <?= ($c['appointment_id'] === null) ? $c['appointment_id'] : 'N/A' ?> </p>
                <p><strong>Patient ID:</strong> <?= $c['patient_id'] ?> </p>
                <p><strong>Nurse ID:</strong> <?= $c['nurse_id'] ?> </p>
                <p><strong>Diagnosis:</strong> <?= $c['diagnosis'] ?> </p>
                <p><strong>Treatment:</strong> <?= $c['treatment'] ?> </p>
                <p><strong>Prescription:</strong> <?= $c['prescription'] ?> </p>
                <p><strong>Notes:</strong> <?= $c['notes'] ?> </p>
                <p><strong>Consultation Date:</strong> <?= $c['consultation_date'] ?> </p>
            </div>

        <?php endforeach; ?>
    
    <?php else: ?>

        <h3>No Consultationss Yet</h3>
 
    <?php endif; ?>
</body>
</html>