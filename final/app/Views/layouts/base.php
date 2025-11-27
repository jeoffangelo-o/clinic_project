<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title><?= esc($pageTitle ?? 'CSPC Clinic') ?> - CSPC Clinic</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler Core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --tblr-primary: #206bc4;
            --tblr-success: #2fb344;
            --tblr-info: #0dcaf0;
            --tblr-warning: #ffc107;
            --tblr-danger: #d63939;
            --tblr-secondary: #6c757d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            width: 100%;
        }

        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Fixed top header */
        .navbar {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 56px;
            z-index: 1030;
            display: flex;
            align-items: center;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
            color: #206bc4 !important;
        }

        .page-wrapper {
            flex: 1;
            display: flex;
            width: 100%;
            overflow: hidden;
        }

        .sidebar {
            width: 260px;
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .navbar-vertical {
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            min-height: 100vh;
            width: 260px;
        }

        .navbar-vertical .nav-link {
            color: #495057;
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .navbar-vertical .nav-link:hover {
            background-color: #f8f9fa;
            color: #206bc4;
        }

        .navbar-vertical .nav-link.active {
            background-color: rgba(32, 107, 196, 0.1);
            color: #206bc4;
            border-left: 3px solid #206bc4;
            padding-left: calc(1.5rem - 3px);
            font-weight: 600;
        }

        .navbar-vertical .nav-link-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .navbar-vertical .nav-link-title {
            flex: 1;
            white-space: nowrap;
        }

        .navbar-vertical .nav-item {
            position: relative;
        }

        .navbar-vertical .collapse.show + .nav-link,
        .navbar-vertical .nav-link[aria-expanded="true"] {
            background-color: #f8f9fa;
            color: #206bc4;
        }

        .navbar-vertical .nav-sm .nav-link {
            padding: 0.5rem 1.5rem 0.5rem 2.75rem;
            font-size: 0.875rem;
        }

        .navbar-vertical .nav-sm .nav-link-bullet {
            margin-right: 0.5rem;
        }

        /* Make the sidebar fixed under the header and give main content a left margin */
        .navbar-vertical {
            position: fixed;
            top: 56px; /* height of header */
            left: 0;
            bottom: 0;
            z-index: 1020;
            overflow-y: auto;
            width: 260px;
        }

        /* Sidebar menu and account layout */
        .navbar-vertical {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 56px);
        }

        .navbar-vertical .menu-container {
            flex: 1 1 auto;
            overflow-y: auto;
            padding-bottom: 0;
        }

        .sidebar-account {
            border-top: 1px solid #eef0f2;
            padding: 0.5rem 1rem;
            background: #ffffff;
            position: sticky;
            bottom: 0;
            box-shadow: 0 -6px 12px rgba(15, 23, 42, 0.03);
            z-index: 10;
            flex-shrink: 0;
        }

        .sidebar-account .avatar {
            width: 32px;
            height: 32px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            flex-shrink: 0;
            font-size: 0.9rem;
        }

        .navbar-vertical .navbar-brand {
            padding: 0.75rem 1rem !important;
            margin: 0 !important;
        }

        .sidebar-account .username {
            font-weight: 600;
            font-size: 0.95rem;
            color: #212529;
        }

        .sidebar-account .role {
            font-size: 0.82rem;
            color: #6c757d;
        }

        .sidebar-account .btn-sm {
            padding: 0.25rem 0.6rem;
            font-size: 0.78rem;
        }

        .page-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1.5rem 1.5rem 2.5rem 1.5rem;
            margin-left: 260px; /* keep space for sidebar */
            background-color: #f8f9fa;
            width: calc(100% - 260px);
            box-sizing: border-box;
            padding-top: 56px; /* ensure content is below header */
        }

        .container-xl {
            width: 100%;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            margin-left: auto;
            margin-right: auto;
            max-width: 100%;
        }

        .page-header {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 2rem;
            padding: 1.5rem;
            border-radius: 0;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #212529;
            margin-bottom: 0.25rem;
        }

        .card {
            border: 0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .btn-primary {
            background-color: #206bc4;
            border-color: #206bc4;
        }

        .btn-primary:hover {
            background-color: #1a5399;
            border-color: #1a5399;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(32, 107, 196, 0.05);
        }

        .form-label {
            font-weight: 500;
            color: #212529;
            margin-bottom: 0.5rem;
        }

        .alert {
            border-radius: 0.375rem;
            border: 0;
            margin-bottom: 1.5rem;
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
        }

        .dropdown-menu {
            border: 0;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .empty {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-header {
            font-size: 4rem;
            color: #206bc4;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .empty-subtitle {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .empty-action {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        footer {
            background-color: #ffffff;
            border-top: 1px solid #dee2e6;
            padding: 1.5rem;
            text-align: center;
            color: #6c757d;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .sidebar,
            .navbar-vertical {
                position: fixed;
                left: -260px;
                top: 0;
                height: 100vh;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.show,
            .navbar-vertical.show {
                left: 0;
            }

            .page-wrapper {
                flex-direction: column;
            }

            .page-content {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Flash Message Toast Container - Top Right */
        .flash-message-container {
            position: fixed;
            top: 70px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .flash-message {
            animation: slideIn 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 6px;
            border: none;
            padding: 14px 16px;
            font-size: 14px;
            font-weight: 500;
        }

        .flash-message.alert-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .flash-message.alert-danger {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        .flash-message.alert-warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
        }

        .flash-message.alert-info {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            color: #0c5460;
        }

        .flash-message .btn-close {
            padding: 0;
            opacity: 0.7;
        }

        .flash-message .btn-close:hover {
            opacity: 1;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 576px) {
            .flash-message-container {
                max-width: 90vw;
                right: 10px;
                left: 10px;
            }
        }
    </style>
    
    <?php if(isset($additionalCSS)): ?>
        <?= $additionalCSS ?>
    <?php endif; ?>
</head>
<body>
    <!-- Flash Message Container - Top Right -->
    <div class="flash-message-container" id="flashContainer"></div>

    <!-- Header/Navbar -->
    <header class="navbar navbar-expand-md navbar-light sticky-top d-print-none">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="<?= base_url('/') ?>">
                    <i class="fas fa-clinic-medical"></i> CSPC
                </a>
            </h1>
            <div class="navbar-nav flex-row order-md-last">
                <div class="nav-item d-none d-md-flex me-3">
                    <div class="btn-list">
                        <?php if(!session()->get('isLoggedIn')): ?>
                            <a href="<?= base_url('/login') ?>" class="btn btn-primary">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="page-wrapper">
        <!-- Page content -->
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Footer removed per design request -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Tabler Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>

    <script>
        // Global Flash Message Handler
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('flashContainer');
            
            // Find all flash messages in the page
            const flashMessages = document.querySelectorAll('[data-flash-message]');
            const shown = new Set();

            flashMessages.forEach(msg => {
                try {
                    const content = msg.textContent || '';
                    // normalize whitespace to avoid minor differences causing duplicates
                    const normalized = content.replace(/\s+/g, ' ').trim();

                    // Skip empty messages
                    if(!normalized) return;

                    // Deduplicate by normalized content
                    if(shown.has(normalized)) {
                        // remove duplicate element to avoid any accidental display
                        msg.remove();
                        return;
                    }

                    // Mark as processed to prevent re-processing this same element
                    msg.setAttribute('data-processed', 'true');
                    shown.add(normalized);

                    const type = msg.dataset.flashMessage || 'info';
                    const alertClass = `alert-${type}`;

                    // Create toast element
                    const toast = document.createElement('div');
                    toast.className = `alert ${alertClass} flash-message alert-dismissible fade show`;
                    toast.setAttribute('role', 'alert');

                    toast.innerHTML = `\n                        ${content}\n                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>\n                    `;

                    container.appendChild(toast);

                    // Remove original element from DOM to be safe
                    msg.remove();

                    // Auto-dismiss after 5 seconds
                    setTimeout(() => {
                        const bsAlert = new bootstrap.Alert(toast);
                        bsAlert.close();
                    }, 5000);
                } catch (e) {
                    // Ignore malformed flash entries
                    console.warn('Flash handling error', e);
                }
            });
        });
    </script>

    <?php if(isset($additionalJS)): ?>
        <?= $additionalJS ?>
    <?php endif; ?>
</body>
</html>
