<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient Info</title>
</head>
<body>
    <h1>View Information</h1>

    <p><strong>Patient ID: </strong><?= esc($p['patient_id'])?></p>
    <p><strong>User ID: </strong><?= ($p['user_id'] !== null) ? esc($p['user_id']) : 'Walk In'?></p>
    <p><strong>First Name: </strong><?= esc($p['first_name'])?></p>
    <p><strong>Middle Name: </strong><?= !empty($p['middle_name']) ? esc($p['middle_name']) : 'N/A' ?></p>
    <p><strong>Last Name :</strong><?= esc($p['last_name'])?></p>
    <p><strong>Gender: </strong><?= esc($p['gender'])?></p>
    <p><strong>Contact Number: </strong><?= esc($p['contact_no'])?></p>
    <p><strong>Address: </strong><?= esc($p['address'])?></p>
    <p><strong>Blood Type: </strong><?= esc($p['blood_type'])?></p>
    <p><strong>Allergies: </strong><?= !empty($p['allergies']) ? esc($p['allergies']) : 'None' ?></p>
    <p><strong>Medical History: </strong><?= !empty($p['medical_history']) ? esc($p['medical_history']) : 'None' ?></p>
    <p><strong>Emergency Contact: </strong><?= esc($p['emergency_contact'])?></p>
    <p><strong>Date Added: </strong><?= esc($p['created_at'])?></p>

    
</body>
</html>