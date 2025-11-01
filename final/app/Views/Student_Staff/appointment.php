<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment</title>
</head>
<body>
    <h1>Appointment</h1>

     <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <?php if(!session()->get('hasPatient')): ?>
        <p>You don't have patient info. <a href="/patient/add">Add</a>  to Continue</p>
    <?php else: ?>

        <button><a href="/appointment/add">Create Appointment</a></button>
    
    <?php endif; ?>

    <?php if(session()->get('role') === 'student' || session()->get('role') === 'staff'): ?>

        <h3>My Appointments</h3>

            <?php if(!empty($appoint)): ?>

                <?php foreach($appoint as $a): ?>
                    <div style="border: 1px solid black">
                        <p><strong>Appointment ID:</strong> <?= $a['appointment_id'] ?></p>
                        <p><strong>Patient ID:</strong> <?= $a['patient_id'] ?></p>
                        <p><strong>Appointment Date:</strong> <?= $a['appointment_date'] ?></p>
                        <p><strong>Purpose:</strong> <?= $a['purpose'] ?></p>
                        <p><strong>Status:</strong> <?= $a['status'] ?></p>
                        <p><strong>Remarks:</strong> <?= (!empty($a['remarks'])) ? $a['remarks'] : 'None'  ?></p>
                        <p><strong>Date Created:</strong> <?= $a['created_at'] ?></p>

                        <button><a href="<?= base_url('/appointment/edit/'.$a['appointment_id']) ?> ">Edit</a></button>
                        <button><a href="<?= base_url('/appointment/delete/'.$a['appointment_id']) ?>" onclick="return confirm('Are you sure to delete this appointment?')">Delete</a></button>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <h4>You don't have appointment yet. </h4>
            <?php endif; ?>

           
    
    <?php elseif(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>

        <form action="/appointment" method="get">
            <select name="status" id="" onchange="this.form.submit()">
                <option value="all" <?= (session()->get('appointment_status') === 'all') ? 'selected' : ''?> >All</option>
                <option value="pending" <?= (session()->get('appointment_status') === 'pending') ? 'selected' : ''?>>Pending</option>
                <option value="approved" <?= (session()->get('appointment_status') === 'approved') ? 'selected' : ''?> >Approved</option>
                <option value="cancelled" <?= (session()->get('appointment_status') === 'cancelled') ? 'selected' : ''?>>Cancelled</option>
                <option value="completed" <?= (session()->get('appointment_status') === 'completed') ? 'selected' : ''?>>Completed</option>
            </select>
        </form>

        <?php if(!empty($appoint)): ?>

                <?php foreach($appoint as $a): ?>

                    <form action="<?= base_url('appointment/update/'.$a['appointment_id']) ?>" method="post">

                        <?php session()->set('activity', 'save') ?>

                        <div style="border: 1px solid black">
                            <p><strong>Appointment ID:</strong> <?= $a['appointment_id'] ?></p>
                            <p><strong>Patient ID:</strong> <?= $a['patient_id'] ?></p>
                            <p><strong>Appointment Date:</strong> <?= $a['appointment_date'] ?></p>
                            <p><strong>Purpose:</strong> <?= $a['purpose'] ?></p>
                            <label><strong>Status:</strong> </label>
                            <select name="status" id="">
                                <option value="pending" <?= ($a['status'] === 'pending') ? 'selected' : ''  ?>>Pending</option>
                                <option value="approved" <?= ($a['status'] === 'approved') ? 'selected' : ''  ?> >Approved</option>
                                <option value="cancelled" <?= ($a['status'] === 'cancelled') ? 'selected' : ''  ?> >Cancelled</option>
                                <option value="completed"> <?= ($a['status'] === 'completed') ? 'selected' : ''  ?> Completed </option>
                            </select>
                            <br><br>
                            <label><strong>Remarks:</strong> </label>
                            <input type="text" name="remarks" id="" value="<?= (!empty($a['remarks'])) ? $a['remarks'] : 'None'  ?>" >

                            <p><strong>Date Created:</strong> <?= $a['created_at'] ?></p>

                            <button type="submit">Save</button>
                            <button><a href="<?= base_url('/appointment/edit/'.$a['appointment_id']) ?> ">Edit</a></button>
                            <button><a href="<?= base_url('/appointment/delete/'.$a['appointment_id']) ?>" onclick="return confirm('Are you sure to delete this appointment?')">Delete</a></button>
                        </div>
                    </form>
                <?php endforeach; ?>

            <?php else: ?>
                <h4>There's no <?= session()->get('appointment_status') ?> appointment yet. </h4>
            <?php endif; ?>

    <?php endif; ?>

 
</body>
</html>