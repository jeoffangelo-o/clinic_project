<!DOCTYPE html>
<html lang="en">

<body>


    <div class="heroe">

        <?php if(!session()->get('isLoggedIn')): ?>

            <h1>Welcome Guest</h1>

            <h2>Please  <a href="/login">login</a> to continue!</h2>

        
        <?php else: ?>

            <h1>Welcome <?= session()->get('username') ?></h1>

            <h2>Your role is  <?= session()->get('role') ?></h2>

            <?php if(session()->get('role') === 'admin'): ?>
                <h1><a href="/list_user">List of User</a></h1>
            
            <?php else: ?>
                <h1><a href="<?= base_url('/edit_user/'.session()->get('user_id')) ?>">Edit My Information</a></h1>
            <?php endif; ?>

            <button><a href="/logout">LOGOUT</a></button>
            
                    <br><br><br>
            <?php if(session()->get('role') === 'nurse' || session()->get('role') === 'admin'): ?>
            
            <div>
                <h3>Patient Management</h3>
                <button><a href="/patient">Manage Patient</a></button>
                <button><a href="/consultation">Consultation</a></button>
                <button><a href="/certificate">Medical Certificates</a></button>
            </div>

            <div>
                <h3>Inventory & Reports</h3>
                <button><a href="/inventory">Manage Inventory</a></button>
                <button><a href="/report">Reports</a></button>
            </div>

            <div>
                <h3>General</h3>
                <button><a href="/announcement">Announcement</a></button>
            </div>

            <?php endif; ?>

            
            
            <div>
                <h3>Appointments</h3>
                <button>
                    <a href="/appointment<?= (session()->get('role') === 'admin' || session()->get('role') === 'nurse') ? '?status=all' : ''   ?>">
                        Appointment
                    </a>
                </button>
            </div>
              
             <?php endif; ?>    
</div>

</body>
</html>