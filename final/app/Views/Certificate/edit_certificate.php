<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Medical Certificate</title>
</head>
<body>
    <h1>Edit Medical Certificate</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <?php if(!empty($cert)): ?>

    <form action="/certificate/update/<?= $cert['certificate_id'] ?>" method="post">

        <label for="patient_id">Patient ID:</label>
        <input type="number" name="patient_id" id="patient_id" value="<?= $cert['patient_id'] ?>" required><br><br>

        <label for="consultation_id">Consultation ID (optional):</label>
        <input type="number" name="consultation_id" id="consultation_id" value="<?= $cert['consultation_id'] ?: '' ?>"><br><br>

        <label for="certificate_type">Certificate Type:</label>
        <select name="certificate_type" id="certificate_type" required>
            <option value="fit_to_study" <?= $cert['certificate_type'] == 'fit_to_study' ? 'selected' : '' ?>>Fit to Study</option>
            <option value="medical_leave" <?= $cert['certificate_type'] == 'medical_leave' ? 'selected' : '' ?>>Medical Leave</option>
            <option value="injury_report" <?= $cert['certificate_type'] == 'injury_report' ? 'selected' : '' ?>>Injury Report</option>
            <option value="others" <?= $cert['certificate_type'] == 'others' ? 'selected' : '' ?>>Others</option>
        </select><br><br>

        <label for="diagnosis_summary">Diagnosis Summary:</label>
        <textarea name="diagnosis_summary" id="diagnosis_summary" rows="4" cols="50"><?= $cert['diagnosis_summary'] ?: '' ?></textarea><br><br>

        <label for="recommendation">Recommendation:</label>
        <textarea name="recommendation" id="recommendation" rows="4" cols="50"><?= $cert['recommendation'] ?: '' ?></textarea><br><br>

        <label for="validity_start">Validity Start Date:</label>
        <input type="date" name="validity_start" id="validity_start" value="<?= $cert['validity_start'] ?: '' ?>"><br><br>

        <label for="validity_end">Validity End Date:</label>
        <input type="date" name="validity_end" id="validity_end" value="<?= $cert['validity_end'] ?: '' ?>"><br><br>

        <label for="file_path">File Path (optional):</label>
        <input type="text" name="file_path" id="file_path" value="<?= $cert['file_path'] ?: '' ?>"><br><br>

        <input type="submit" value="Update Certificate">
        
    </form>

    <?php else: ?>
        <p>Certificate not found</p>
    <?php endif; ?>
</body>
</html>
