<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation</title>
</head>
<body>
    <h1>Consultation</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <br>
    
    <button><a href="/consultation/add">Add Consultation</a></button>

    <?php if(!empty($consult)): ?>

        <?php foreach($consult as $c): ?>

            <div style="border: 1px solid black;">

                <p><strong>Consultation ID:</strong> <?= esc($c['consultation_id']) ?> </p>
                <p><strong>Appointment ID:</strong> <?= ($c['appointment_id'] !== null) ? esc($c['appointment_id']) : 'N/A' ?> </p>
                <p><strong>Patient ID:</strong> <?= esc($c['patient_id']) ?> </p>
                <p><strong>Nurse ID:</strong> <?= esc($c['nurse_id']) ?> </p>
                <p><strong>Diagnosis:</strong> <?= esc($c['diagnosis']) ?> </p>
                <p><strong>Treatment:</strong> <?= esc($c['treatment']) ?> </p>
                <p><strong>Prescription:</strong> <?= esc($c['prescription']) ?> </p>
                <p><strong>Notes:</strong> <?= ($c['notes'] === null || $c['notes'] === '') ? 'None' : esc($c['notes']) ?> </p>
                <p><strong>Consultation Date:</strong> <?= esc($c['consultation_date']) ?> </p>
                <button><a href="<?= base_url('/consultation/edit/'.$c['consultation_id']) ?>">Edit</a></button>
                <button><a href="<?= base_url('/consultation/delete/'.$c['consultation_id']) ?>">Delete</a></button>
            </div>

        <?php endforeach; ?>
    
    <?php else: ?>

        <h3>No Consultationss Yet</h3>
      
    <?php endif; ?>
</body>
</html>