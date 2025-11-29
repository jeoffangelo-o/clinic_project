<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Login - CSPC Clinic</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --tblr-primary: #206bc4;
        }
        
        html, body {
            height: 100%;
        }
        
        body {
            background: linear-gradient(135deg, #206bc4 0%, #0d6efd 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        .login-page {
            width: 100%;
            max-width: 420px;
            padding: 15px;
        }
        
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: 0;
        }
        
        .form-label {
            font-weight: 500;
            color: #212529;
        }
        
        .btn-primary {
            background-color: #206bc4;
            border-color: #206bc4;
            padding: 0.5rem 1rem;
        }
        
        .btn-primary:hover {
            background-color: #1a5399;
            border-color: #1a5399;
        }
        
        .login-branding {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-branding h1 {
            color: white;
            font-weight: bold;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .login-branding p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }
        
        .alert {
            border: 0;
            border-radius: 0.375rem;
        }
    </style>
</head>
<body>
    <div class="login-page">
        <!-- Logo/Branding -->
        <div class="login-branding">
            <h1><i class="fas fa-clinic-medical"></i> CSPC Clinic</h1>
            <p>Healthcare Management System</p>
        </div>

        <div class="card">
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Sign in to your account</h2>

                <?php if(session()->getFlashData('message')): 
                    $message = session()->getFlashData('message');
                    $alertClass = (strpos($message, 'Error') !== false || strpos($message, 'Invalid') !== false) ? 'danger' : 'success';
                ?>
                    <div class="alert alert-<?= $alertClass ?> alert-dismissible fade show" role="alert">
                        <?= $message ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/auth') ?>" method="post" class="form-group">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter your username" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Sign in
                        </button>
                    </div>
                </form>

                <div class="text-center text-muted mt-4">
                    <p>Don't have an account? <a href="<?= base_url('/register') ?>" class="fw-bold text-primary">Create an account</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tabler JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
</body>
</html>