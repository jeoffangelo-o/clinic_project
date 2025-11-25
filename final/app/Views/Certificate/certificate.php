<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Medical Certificates</title>
</head>
<body>
    <h1>Manage Medical Certificates</h1>

    <button><a href="/certificate/add">Add Certificate</a></button>

    <h2>List of Medical Certificates</h2>

    <table border='1'>
        <tr>
            <th>Certificate ID</th>
            <th>Patient ID</th>
            <th>Certificate Type</th>
            <th>Validity Start</th>
            <th>Validity End</th>
            <th>Issued Date</th>
            <th>Actions</th>
        </tr>

        <?php if(!empty($certificate)): ?>

            <?php foreach($certificate as $c): ?>

                <tr>
                    <td><?= esc($c['certificate_id']) ?></td>
                    <td><?= esc($c['patient_id']) ?></td>
                    <td><?= esc(ucfirst(str_replace('_', ' ', $c['certificate_type']))) ?></td>
                    <td><?= esc($c['validity_start'] ?: 'N/A') ?></td>
                    <td><?= esc($c['validity_end'] ?: 'N/A') ?></td>
                    <td><?= esc($c['issued_date']) ?></td>

                    <td>
                        <a href="<?= base_url('/certificate/view/'.$c['certificate_id'])?>">View</a>
                        <a href="<?= base_url('/certificate/edit/'.$c['certificate_id'])?>">Edit</a>
                        <a href="<?= base_url('/certificate/delete/'.$c['certificate_id'])?>" onclick="return confirm('Are you sure to delete certificate #<?= esc($c['certificate_id']) ?>?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>

        <?php else: ?>
            <tr>
                <td colspan="7">No Certificates</td>
            </tr>
        <?php endif; ?>

    </table>
</body>
</html>
