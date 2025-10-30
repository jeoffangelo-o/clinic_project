<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
</head>
<body>
    <h1>Add Patient</h1>

    <form action="/patient/store" method="post">
        <label for="user_id">User ID: </label>
        <input type="number" name="user_id" id="" placeholder="leave blank if none"><br><br>
        <label for="user_id">First Name:</label>
        <input type="text" name="first_name" id="" required><br><br>
        <label for="user_id">Middle Name:</label>
        <input type="text" name="middle_name" id=""><br><br>
        <label for="user_id">Last Name:</label>
        <input type="text" name="last_name" id=""><br><br>
        <label for="">Gender:</label>
        <select name="gender" id="">
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select><br><br>
        <label for="">Birth Date:</label>
        <input type="date" name="birth_date" id="" required><br><br>
        <label for="">Phone Number:</label>
        <input type="text" name="contact_no"><br><br>
        <label for="">Address:</label>
        <input type="text" name="address"><br><br>
        <label for="">Address:</label>
        <input type="text" name="address"><br><br>
        <label for="">Blood Type:</label>
        <select name="blood_type" id="" required>
            <option value="">--Select--</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
        </select><br><br>
        <label for="">Allergies:</label>
        <input type="text" name="allergies"><br><br>
        <label for="">Medical History:</label>
        <input type="text" name="medical_history"><br><br>
        <label for="">Emergency Contact Number:</label>
        <input type="text" name="emergency_contact"><br><br>
        <input type="submit" value="Add Patient">
    </form>
</body>
</html>