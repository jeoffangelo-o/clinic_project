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
                <button><a href="/patient">Manage Patient</a></button>
                <button><a href="#">Consultation</a></button>

            </div>

            <?php endif; ?>

            
            
            <div>
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