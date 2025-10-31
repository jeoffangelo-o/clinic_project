<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patient</title>
</head>
<body>
    <h1>Manage Patient</h1>

    <button><a href="/patient/add">Add Patient</a></button>

    <h2>List of Patient</h2>

    <table border='1'>
        <tr>
            <th>Patient ID</th>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Date Added</th>
            <th>Actions</th>
        </tr>

        <?php if(!empty($patient)): ?>

            <?php foreach($patient as $p): ?>

                <tr>
                    <td><?= $p['patient_id'] ?></td>
                    <td><?= (!empty($p['user_id'])) ? $p['user_id'] : 'Walk In'?></td>
                    <td><?= $p['last_name']  . ', ' . $p['first_name'] . ' ' . $p['middle_name'] ?></td>
                    <td><?= $p['gender'] ?></td>
                    <td><?= $p['created_at'] ?></td>

                    <td>
                        <a href="<?= base_url('/patient/view/'.$p['patient_id'])?>">View</a>
                        <a href="<?= base_url('/patient/edit/'.$p['patient_id'])?>">Edit</a>
                        <a href="#">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="6">No Patient</td>
            </tr>
        <?php endif; ?>

    </table>
</body>
</html>