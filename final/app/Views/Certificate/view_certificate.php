<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Medical Certificate</title>
</head>
<body>
    <h1>View Medical Certificate</h1>

    <a href="/certificate">Back to Certificates</a>

    <h2>Certificate Details</h2>

    <?php if(!empty($cert)): ?>
        <table border='1'>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Certificate ID</td>
                <td><?= esc($cert['certificate_id']) ?></td>
            </tr>
            <tr>
                <td>Patient ID</td>
                <td><?= esc($cert['patient_id']) ?></td>
            </tr>
            <tr>
                <td>Consultation ID</td>
                <td><?= esc($cert['consultation_id'] ?: 'N/A') ?></td>
            </tr>
            <tr>
                <td>Certificate Type</td>
                <td><?= esc(ucfirst(str_replace('_', ' ', $cert['certificate_type']))) ?></td>
            </tr>
            <tr>
                <td>Diagnosis Summary</td>
                <td><?= esc($cert['diagnosis_summary'] ?: 'N/A') ?></td>
            </tr>
            <tr>
                <td>Recommendation</td>
                <td><?= esc($cert['recommendation'] ?: 'N/A') ?></td>
            </tr>
            <tr>
                <td>Validity Start</td>
                <td><?= esc($cert['validity_start'] ?: 'N/A') ?></td>
            </tr>
            <tr>
                <td>Validity End</td>
                <td><?= esc($cert['validity_end'] ?: 'N/A') ?></td>
            </tr>
            <tr>
                <td>Issued Date</td>
                <td><?= esc($cert['issued_date']) ?></td>
            </tr>
            <tr>
                <td>File Path</td>
                <td><?= esc($cert['file_path'] ?: 'N/A') ?></td>
            </tr>
        </table>

        <br><br>
        <a href="<?= base_url('/certificate/edit/'.$cert['certificate_id']) ?>">Edit</a>
        <a href="<?= base_url('/certificate/delete/'.$cert['certificate_id']) ?>" onclick="return confirm('Are you sure?')">Delete</a>

    <?php else: ?>
        <p>Certificate not found</p>
    <?php endif; ?>
</body>
</html>
