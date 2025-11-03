<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Consultation</title>
</head>
<body>
    <h1>Add Consultation</h1>

    <?php if(session()->get('role') === 'admin' || session()->get('role')):  ?>

        <form action="/consultation" method="get" >
            <label for="">Service:</label>
            <select name="service" id="" onchange="this.form.submit()">
                <option value="walkin" <?= (session()->get('service') === 'walkin') ? 'selected' : '' ?>>Walk-In</option>
                <option value="appoint" <?= (session()->get('service') === 'appoint') ? 'selected' : '' ?>>Appointment</option>
            </select>
        </form>
        
        <form action="/consultation/add" method="post">

            <?php if(session()->get('service') === 'walkin'): ?>
                
                

            <?php else: ?>
                
            <?php endif; ?>

        </form>

    <?php else: ?>

    <?php endif; ?>

</body>
</html>