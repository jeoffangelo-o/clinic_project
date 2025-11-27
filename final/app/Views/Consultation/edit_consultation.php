<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Consultation</title>
</head>
<body>
    <h1>Edit Consultation</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'):  ?>

        <form action="<?= base_url('/consultation/update/'.$consult['consultation_id']) ?>" method="post">
            <?= csrf_field() ?>

            <p>Appointment ID: <?= ( $consult['appointment_id'] === null )? 'N/A' :  $consult['appointment_id']  ?></p>
            <p>Patient ID: <?=  $consult['patient_id'] ?></p>
            <label for="">Diagnosis:</label>
            <input type="text" name="diagnosis" id="" value="<?=  $consult['diagnosis'] ?>" required><br><br>
            <label for="">Treatment:</label>
            <input type="text" name="treatment" id="" value="<?=  $consult['treatment'] ?>" required><br><br>
            <label for="">Prescription:</label>
            <input type="text" name="prescription" id="" value="<?=  $consult['prescription'] ?>" required><br><br>
            <label for="">Notes:</label>
            <input type="text" name="notes" id="" value="<?=  $consult['notes'] ?>" ><br><br>
            
            <?php if(!empty($medicines)): ?>
                <p><strong>Medicines Used in this Consultation:</strong></p>
                <ul>
                    <?php foreach($medicines as $med): ?>
                        <li><?= esc($med['item_name']) ?> - <?= esc($med['quantity_used']) ?> <?= esc($med['unit']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p><strong>Medicines Used:</strong> None allocated</p>
            <?php endif; ?>
            <br>
            
            <input type="submit" value="Update Consultation"> 

        </form>

    <?php else: ?>

    <?php endif; ?>

</body>
</html>