<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medical Certificate</title>
</head>
<body>
    <h1>Add Medical Certificate</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <form action="/certificate/store" method="post">
        <?= csrf_field() ?>
        <label for="patient_id">Patient ID:</label>
        <input type="number" name="patient_id" id="patient_id" required><br><br>

        <label for="consultation_id">Consultation ID (optional):</label>
        <input type="number" name="consultation_id" id="consultation_id"><br><br>

        <label for="certificate_type">Certificate Type:</label>
        <select name="certificate_type" id="certificate_type" required>
            <option value="">--Select--</option>
            <option value="fit_to_study">Fit to Study</option>
            <option value="medical_leave">Medical Leave</option>
            <option value="injury_report">Injury Report</option>
            <option value="others">Others</option>
        </select><br><br>

        <label for="diagnosis_summary">Diagnosis Summary:</label>
        <textarea name="diagnosis_summary" id="diagnosis_summary" rows="4" cols="50"></textarea><br><br>

        <label for="recommendation">Recommendation:</label>
        <textarea name="recommendation" id="recommendation" rows="4" cols="50"></textarea><br><br>

        <label for="validity_start">Validity Start Date:</label>
        <input type="date" name="validity_start" id="validity_start"><br><br>

        <label for="validity_end">Validity End Date:</label>
        <input type="date" name="validity_end" id="validity_end"><br><br>

        <label for="file_path">File Path (optional):</label>
        <input type="text" name="file_path" id="file_path"><br><br>

        <input type="submit" value="Add Certificate">
        
    </form>
</body>
</html>
