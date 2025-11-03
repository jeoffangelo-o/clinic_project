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
                <option value="walkin">Walk-In</option>
                <option value="appoint">Appointment</option>
            </select>
        </form>
        
        <form action="/consultation/add" method="post">

            <?php if(session()->get('walkIn')): ?>

            <?php else: ?>
                
            <?php endif; ?>

        </form>

    <?php else: ?>

    <?php endif; ?>

</body>
</html>