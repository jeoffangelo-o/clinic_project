<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
</head>
<body>
    <h1>Generate Report</h1>

    <?php if(session()->getFlashData('message')): ?>
        <p><?= session()->getFlashData('message') ?></p>
    <?php endif; ?>

    <form action="/report/store" method="post">

        <label for="report_type">Report Type:</label>
        <select name="report_type" id="report_type" required>
            <option value="">--Select--</option>
            <option value="patient">Patient Report</option>
            <option value="consultation">Consultation Report</option>
            <option value="appointment">Appointment Report</option>
            <option value="inventory">Inventory Report</option>
            <option value="announcement">Announcement Report</option>
            <option value="comprehensive">Comprehensive Report (All Data)</option>
        </select><br><br>

        <label for="file_path">File Path (optional):</label>
        <input type="text" name="file_path" id="file_path" placeholder="e.g., /uploads/report_name.pdf"><br><br>

        <input type="submit" value="Generate Report">
        
    </form>
</body>
</html>
