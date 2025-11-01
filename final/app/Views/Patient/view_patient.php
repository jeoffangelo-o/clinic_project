<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient Info</title>
</head>
<body>
    <h1>View Information</h1>

    <p><strong>Patient ID: </strong><?= $p['patient_id']?></p>
    <p><strong>User ID: </strong><?= ($p['user_id'] !== null) ? $p['user_id'] : 'Walk In'?></p>
    <p><strong>First Name: </strong><?= $p['first_name']?></p>
    <p><strong>Middle Name: </strong><?= !empty($p['middle_name']) ? $p['middle_name'] : 'N/A' ?></p>
    <p><strong>Last Name :</strong><?= $p['last_name']?></p>
    <p><strong>Gender: </strong><?= $p['gender']?></p>
    <p><strong>Contact Number: </strong><?= $p['contact_no']?></p>
    <p><strong>Address: </strong><?= $p['address']?></p>
    <p><strong>Blood Type: </strong><?= $p['blood_type']?></p>
    <p><strong>Allergies: </strong><?= !empty($p['allergies']) ? $p['allergies'] : 'None' ?></p>
    <p><strong>Medical History: </strong><?= !empty($p['medical_history']) ? $p['medical_history'] : 'None' ?></p>
    <p><strong>Emergency Contact: </strong><?= $p['emergency_contact']?></p>
    <p><strong>Date Added: </strong><?= $p['created_at']?></p>

    
</body>
</html>