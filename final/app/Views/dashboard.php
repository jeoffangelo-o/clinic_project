<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>

<?php if(!$isLoggedIn): ?>
    <div class="empty">
        <div class="empty-header">
            <i class="fas fa-lock"></i>
        </div>
        <p class="empty-title">Welcome Guest</p>
        <p class="empty-subtitle">Please login to continue using CSPC Clinic</p>
        <div class="empty-action">
            <a href="<?= base_url('/login') ?>" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
            <a href="<?= base_url('/register') ?>" class="btn btn-secondary">
                <i class="fas fa-user-plus"></i> Register
            </a>
        </div>
    </div>
<?php else: ?>
    <!-- Welcome Section -->
     <br><br><br>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title">Welcome back, <strong><?= esc($username) ?></strong>!</h3>
                            <p class="text-muted">You are logged in as <strong><?= ucfirst(esc($role)) ?></strong></p>
                        </div>
                        <div class="col-auto">
                            <div class="avatar avatar-lg" style="background-color: #206bc4; color: white;">
                                <span><?= strtoupper(substr($username, 0, 1)) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADMIN DASHBOARD -->
    <?php if($role === 'admin'): ?>
        <div class="row mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-truncate">
                            <h3 class="card-title text-reset fw-bold">
                                <i class="fas fa-users"></i> User Management
                            </h3>
                            <p class="text-muted text-sm">Manage system users and roles</p>
                        </div>
                    </div>
                    <a href="<?= base_url('/list_user') ?>" class="card-btn">View Users</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-truncate">
                            <h3 class="card-title text-reset fw-bold">
                                <i class="fas fa-user-injured"></i> Patient Management
                            </h3>
                            <p class="text-muted text-sm">Manage patient records</p>
                        </div>
                    </div>
                    <a href="<?= base_url('/patient') ?>" class="card-btn">View Patients</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-truncate">
                            <h3 class="card-title text-reset fw-bold">
                                <i class="fas fa-stethoscope"></i> Consultations
                            </h3>
                            <p class="text-muted text-sm">View all consultations</p>
                        </div>
                    </div>
                    <a href="<?= base_url('/consultation') ?>" class="card-btn">View Consultations</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-truncate">
                            <h3 class="card-title text-reset fw-bold">
                                <i class="fas fa-file-alt"></i> Reports
                            </h3>
                            <p class="text-muted text-sm">Generate and view reports</p>
                        </div>
                    </div>
                    <a href="<?= base_url('/report') ?>" class="card-btn">View Reports</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-truncate">
                            <h3 class="card-title text-reset fw-bold">
                                <i class="fas fa-calendar-check"></i> Appointments
                            </h3>
                            <p class="text-muted text-sm">Manage appointments</p>
                        </div>
                    </div>
                    <a href="<?= base_url('/appointment') ?>" class="card-btn">View Appointments</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-truncate">
                            <h3 class="card-title text-reset fw-bold">
                                <i class="fas fa-boxes"></i> Inventory
                            </h3>
                            <p class="text-muted text-sm">Manage medical supplies</p>
                        </div>
                    </div>
                    <a href="<?= base_url('/inventory') ?>" class="card-btn">View Inventory</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-truncate">
                            <h3 class="card-title text-reset fw-bold">
                                <i class="fas fa-bullhorn"></i> Announcements
                            </h3>
                            <p class="text-muted text-sm">Create and manage announcements</p>
                        </div>
                    </div>
                    <a href="<?= base_url('/announcement') ?>" class="card-btn">View Announcements</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="text-truncate">
                            <h3 class="card-title text-reset fw-bold">
                                <i class="fas fa-certificate"></i> Certificates
                            </h3>
                            <p class="text-muted text-sm">Generate certificates</p>
                        </div>
                    </div>
                    <a href="<?= base_url('/certificate') ?>" class="card-btn">View Certificates</a>
                </div>
            </div>
        </div>

    <!-- NURSE DASHBOARD -->
    <?php elseif($role === 'nurse'): ?>
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-user-injured"></i> Patient Management
                        </h3>
                        <p class="text-muted text-sm">Manage patient records and medical history</p>
                    </div>
                    <a href="<?= base_url('/patient') ?>" class="card-btn">View Patients</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-stethoscope"></i> Consultations
                        </h3>
                        <p class="text-muted text-sm">Create and manage consultations</p>
                    </div>
                    <a href="<?= base_url('/consultation') ?>" class="card-btn">View Consultations</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-calendar-check"></i> Appointments
                        </h3>
                        <p class="text-muted text-sm">Review and approve appointments</p>
                    </div>
                    <a href="<?= base_url('/appointment') ?>" class="card-btn">View Appointments</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-boxes"></i> Inventory
                        </h3>
                        <p class="text-muted text-sm">Manage medical supplies and stock</p>
                    </div>
                    <a href="<?= base_url('/inventory') ?>" class="card-btn">View Inventory</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-file-alt"></i> Reports
                        </h3>
                        <p class="text-muted text-sm">Generate and export reports</p>
                    </div>
                    <a href="<?= base_url('/report') ?>" class="card-btn">View Reports</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-bullhorn"></i> Announcements
                        </h3>
                        <p class="text-muted text-sm">Create and manage announcements</p>
                    </div>
                    <a href="<?= base_url('/announcement') ?>" class="card-btn">View Announcements</a>
                </div>
            </div>
        </div>

    <!-- STAFF DASHBOARD -->
    <?php elseif($role === 'staff'): ?>
        <div class="row mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-user-injured"></i> Patients
                        </h3>
                        <p class="text-muted text-sm">View patient information</p>
                    </div>
                    <a href="<?= base_url('/patient') ?>" class="card-btn">View Patients</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-stethoscope"></i> Consultations
                        </h3>
                        <p class="text-muted text-sm">View consultation records</p>
                    </div>
                    <a href="<?= base_url('/consultation') ?>" class="card-btn">View Consultations</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-calendar-check"></i> Appointments
                        </h3>
                        <p class="text-muted text-sm">View appointment schedule</p>
                    </div>
                    <a href="<?= base_url('/appointment') ?>" class="card-btn">View Appointments</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-boxes"></i> Inventory
                        </h3>
                        <p class="text-muted text-sm">View available supplies</p>
                    </div>
                    <a href="<?= base_url('/inventory') ?>" class="card-btn">View Inventory</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-file-alt"></i> Reports
                        </h3>
                        <p class="text-muted text-sm">View system reports</p>
                    </div>
                    <a href="<?= base_url('/report') ?>" class="card-btn">View Reports</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-bullhorn"></i> Announcements
                        </h3>
                        <p class="text-muted text-sm">Read announcements</p>
                    </div>
                    <a href="<?= base_url('/announcement') ?>" class="card-btn">View Announcements</a>
                </div>
            </div>
        </div>

    <!-- STUDENT DASHBOARD -->
    <?php elseif($role === 'student'): ?>
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-user-injured"></i> Patients
                        </h3>
                        <p class="text-muted text-sm">View patient information</p>
                    </div>
                    <a href="<?= base_url('/patient') ?>" class="card-btn">View Patients</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-stethoscope"></i> Consultations
                        </h3>
                        <p class="text-muted text-sm">View consultation records</p>
                    </div>
                    <a href="<?= base_url('/consultation') ?>" class="card-btn">View Consultations</a>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title fw-bold">
                            <i class="fas fa-calendar-check"></i> Appointments
                        </h3>
                        <p class="text-muted text-sm">View my appointments</p>
                    </div>
                    <a href="<?= base_url('/appointment') ?>" class="card-btn">View Appointments</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?= $this->endSection() ?>