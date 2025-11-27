<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>

<!-- Sidebar Navigation -->
<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark" style="background-color: #ffffff; width: 260px; flex-shrink: 0; border-right: 1px solid #dee2e6;">
    <div class="container-fluid menu-container" style="padding: 0;">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark" style="padding: 0.75rem 1rem; margin: 0;">
            <a href="<?= base_url('/') ?>" style="color: #206bc4;">
                <i class="fas fa-clinic-medical"></i> CSPC
            </a>
        </h1>
        <ul class="navbar-nav pt-lg-3" style="padding: 0 0 2rem 0;">
                <!-- Dashboard -->
                <li class="nav-item" style="list-style: none;">
                    <a class="nav-link <?= uri_string() == '' ? 'active' : '' ?>" href="<?= base_url('/') ?>" style="padding: 0.75rem 1.5rem; display: flex; align-items: center; color: #495057; text-decoration: none; transition: all 0.3s ease;">
                        <span class="nav-link-icon d-md-none d-lg-inline-block" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                            <i class="fas fa-home"></i>
                        </span>
                        <span class="nav-link-title" style="flex: 1;">Dashboard</span>
                    </a>
                </li>

                <?php if(session()->get('role') === 'admin'): ?>
                    <!-- Admin Section -->
                    <li class="nav-item" style="list-style: none;">
                        <a class="nav-link" href="#navbar-admin" data-bs-toggle="collapse" role="button" aria-expanded="false" style="padding: 0.75rem 1.5rem; display: flex; align-items: center; color: #495057; text-decoration: none; transition: all 0.3s ease; cursor: pointer;">
                            <span class="nav-link-icon d-md-none d-lg-inline-block" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                                <i class="fas fa-users-cog"></i>
                            </span>
                            <span class="nav-link-title">Administration</span>
                        </a>
                        <div class="collapse" id="navbar-admin">
                            <ul class="nav nav-sm flex-column" style="padding-left: 0;">
                                <li class="nav-item" style="list-style: none;">
                                    <a class="nav-link <?= strpos(uri_string(), 'list_user') !== false ? 'active' : '' ?>" href="<?= base_url('/list_user') ?>" style="padding: 0.5rem 1.5rem 0.5rem 2.75rem; display: flex; align-items: center; color: #495057; text-decoration: none; transition: all 0.3s ease; font-size: 0.875rem;">
                                        <span class="nav-link-bullet" style="margin-right: 0.5rem;">•</span>
                                        <span>Manage Users</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                <?php endif; ?>

                <!-- Patient Management -->
                <?php if(in_array(session()->get('role'), ['admin', 'nurse', 'staff'])): ?>
                    <li class="nav-item" style="list-style: none;">
                        <a class="nav-link <?= strpos(uri_string(), 'patient') !== false ? 'active' : '' ?>" href="<?= base_url('/patient') ?>" style="padding: 0.75rem 1.5rem; display: flex; align-items: center; color: #495057; text-decoration: none; transition: all 0.3s ease;">
                            <span class="nav-link-icon d-md-none d-lg-inline-block" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                                <i class="fas fa-users"></i>
                            </span>
                            <span class="nav-link-title">Patients</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Consultations -->
                <?php if(in_array(session()->get('role'), ['admin', 'nurse', 'staff', 'student'])): ?>
                    <li class="nav-item" style="list-style: none;">
                        <a class="nav-link <?= strpos(uri_string(), 'consultation') !== false ? 'active' : '' ?>" href="<?= base_url('/consultation') ?>" style="padding: 0.75rem 1.5rem; display: flex; align-items: center; color: #495057; text-decoration: none; transition: all 0.3s ease;">
                            <span class="nav-link-icon d-md-none d-lg-inline-block" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                                <i class="fas fa-stethoscope"></i>
                            </span>
                            <span class="nav-link-title">Consultations</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Appointments -->
                <li class="nav-item" style="list-style: none;">
                    <a class="nav-link <?= strpos(uri_string(), 'appointment') !== false ? 'active' : '' ?>" href="<?= base_url('/appointment') ?>" style="padding: 0.75rem 1.5rem; display: flex; align-items: center; color: #495057; text-decoration: none; transition: all 0.3s ease;">
                        <span class="nav-link-icon d-md-none d-lg-inline-block" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                            <i class="fas fa-calendar-check"></i>
                        </span>
                        <span class="nav-link-title">Appointments</span>
                    </a>
                </li>

                <!-- Inventory -->
                <?php if(in_array(session()->get('role'), ['admin', 'nurse', 'staff'])): ?>
                    <li class="nav-item" style="list-style: none;">
                        <a class="nav-link <?= strpos(uri_string(), 'inventory') !== false ? 'active' : '' ?>" href="<?= base_url('/inventory') ?>" style="padding: 0.75rem 1.5rem; display: flex; align-items: center; color: #495057; text-decoration: none; transition: all 0.3s ease;">
                            <span class="nav-link-icon d-md-none d-lg-inline-block" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                                <i class="fas fa-boxes"></i>
                            </span>
                            <span class="nav-link-title">Inventory</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Reports -->
                <?php if(in_array(session()->get('role'), ['admin', 'nurse', 'staff'])): ?>
                    <li class="nav-item" style="list-style: none;">
                        <a class="nav-link <?= strpos(uri_string(), 'report') !== false ? 'active' : '' ?>" href="<?= base_url('/report') ?>" style="padding: 0.75rem 1.5rem; display: flex; align-items: center; color: #495057; text-decoration: none; transition: all 0.3s ease;">
                            <span class="nav-link-icon d-md-none d-lg-inline-block" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            <span class="nav-link-title">Reports</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Certificates -->
                <?php if(in_array(session()->get('role'), ['admin', 'nurse', 'staff'])): ?>
                    <li class="nav-item" style="list-style: none;">
                        <a class="nav-link <?= strpos(uri_string(), 'certificate') !== false ? 'active' : '' ?>" href="<?= base_url('/certificate') ?>" style="padding: 0.75rem 1.5rem; display: flex; align-items: center; color: #495057; text-decoration: none; transition: all 0.3s ease;">
                            <span class="nav-link-icon d-md-none d-lg-inline-block" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                                <i class="fas fa-certificate"></i>
                            </span>
                            <span class="nav-link-title">Certificates</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Announcements -->
                <li class="nav-item" style="list-style: none;">
                    <a class="nav-link <?= strpos(uri_string(), 'announcement') !== false ? 'active' : '' ?>" href="<?= base_url('/announcement') ?>" style="padding: 0.75rem 1.5rem; display: flex; align-items: center; color: #495057; text-decoration: none; transition: all 0.3s ease;">
                        <span class="nav-link-icon d-md-none d-lg-inline-block" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; margin-right: 0.75rem;">
                            <i class="fas fa-bullhorn"></i>
                        </span>
                        <span class="nav-link-title">Announcements</span>
                    </a>
                </li>
            </ul>
    
    <!-- Sidebar account block (moved from top navbar) -->
    <div class="container-fluid sidebar-account" style="padding: 0.75rem 1rem;">
        <?php if(session()->get('isLoggedIn')): ?>
            <div class="d-flex align-items-center">
                <div class="avatar" style="background-color: #206bc4; margin-right: 0.75rem;">
                    <?= strtoupper(substr(session()->get('username') ?? '', 0, 1)) ?>
                </div>
                <div style="flex:1;">
                    <div class="username"><?= esc(session()->get('username')) ?></div>
                    <div class="role"><?= esc(ucfirst(session()->get('role') ?? '')) ?></div>
                </div>
                <div class="ms-2 d-flex" style="gap:0.5rem;">
                    <a href="<?= base_url('/edit_user/'.session()->get('user_id')) ?>" class="btn btn-sm btn-icon btn-ghost-secondary" title="Edit Profile"><i class="fas fa-edit"></i></a>
                    <a href="<?= base_url('/logout') ?>" class="btn btn-sm btn-icon btn-danger" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        <?php else: ?>
            <a href="<?= base_url('/login') ?>" class="btn btn-primary w-100">Login</a>
        <?php endif; ?>
    </div>

</aside>

<!-- Main Content Area -->
<div class="page-content" style="flex: 1; overflow-y: auto; padding: 2rem 0; background-color: #f8f9fa;">
    <div class="container-xl">
        <!-- Page title -->
        <?php if(isset($pageTitle)): ?>
            <div class="page-header d-print-none">
                <div class="row align-items-center mb-3">
                    <div class="col">
                        <h2 class="page-title">
                            <?php if(isset($pageIcon)): ?>
                                <i class="<?= $pageIcon ?>"></i>
                            <?php endif; ?>
                            <?= $pageTitle ?>
                        </h2>
                        <?php if(isset($pageSubtitle)): ?>
                            <p class="text-muted"><?= $pageSubtitle ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if(isset($pageButton)): ?>
                        <div class="col-auto">
                            <?= $pageButton ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Page content -->
        <?= $this->renderSection('mainContent') ?>
    </div>
</div>

<?= $this->endSection() ?>
