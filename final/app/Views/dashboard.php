<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>
<br><br><br>
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
    <!-- Header Section -->
    <div class="page-wrapper">
        <div class="container-xl">
            <!-- Welcome Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="page-title">Dashboard</h1>
                    <p class="text-muted">Welcome back, <strong><?= esc($username) ?></strong>! (<?= ucfirst(esc($role)) ?>)</p>
                </div>
            </div>

            <!-- KPI Cards Row 1 - Role-based display -->
            <div class="row row-deck row-cards">
                <!-- Student/Staff Dashboard -->
                <?php if($role === 'student' || $role === 'staff'): ?>
                    <!-- My Patient -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-blue text-white">
                                            <i class="fas fa-user-injured"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= isset($my_patient) ? 'Registered' : 'Not Registered' ?></span>
                                        </div>
                                        <div class="text-muted text-sm">My Patient Status</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Consultations -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-green text-white">
                                            <i class="fas fa-stethoscope"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= $total_consultations ?? 0 ?></span>
                                        </div>
                                        <div class="text-muted text-sm">My Consultations</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Appointments -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-yellow text-white">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= $total_appointments ?? 0 ?></span>
                                        </div>
                                        <div class="text-muted text-sm">My Appointments</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Patient Details -->
                    <?php if(isset($my_patient)): ?>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card bg-primary-light">
                            <div class="card-body">
                                <div class="text-primary">
                                    <i class="fas fa-info-circle"></i> Patient ID
                                </div>
                                <h3 class="mb-0 text-primary"><?= $my_patient['patient_id'] ?></h3>
                                <p class="text-muted text-sm mt-2">Your Patient Record</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                <!-- Nurse Dashboard -->
                <?php elseif($role === 'nurse'): ?>
                    <!-- Total Patients -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-blue text-white">
                                            <i class="fas fa-user-injured"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= $total_patients ?? 0 ?></span>
                                        </div>
                                        <div class="text-muted text-sm">Total Patients</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Consultations -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-green text-white">
                                            <i class="fas fa-stethoscope"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= $total_consultations ?? 0 ?></span>
                                        </div>
                                        <div class="text-muted text-sm">Total Consultations</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Appointments -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-yellow text-white">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= $total_appointments ?? 0 ?></span>
                                        </div>
                                        <div class="text-muted text-sm">Total Appointments</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Items -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-red text-white">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= $total_inventory ?? 0 ?></span>
                                        </div>
                                        <div class="text-muted text-sm">Inventory Items</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Admin Dashboard -->
                <?php else: ?>
                    <!-- Total Patients -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-blue text-white">
                                            <i class="fas fa-user-injured"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= $total_patients ?? 0 ?></span>
                                        </div>
                                        <div class="text-muted text-sm">Total Patients</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Consultations -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-green text-white">
                                            <i class="fas fa-stethoscope"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= $total_consultations ?? 0 ?></span>
                                        </div>
                                        <div class="text-muted text-sm">Total Consultations</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Appointments -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-yellow text-white">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= $total_appointments ?? 0 ?></span>
                                        </div>
                                        <div class="text-muted text-sm">Total Appointments</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Items -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-baseline">
                                    <div class="me-3">
                                        <div class="btn btn-icon btn-red text-white">
                                            <i class="fas fa-boxes"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="h3 mb-0">
                                            <span class="text-dark"><?= $total_inventory ?? 0 ?></span>
                                        </div>
                                        <div class="text-muted text-sm">Inventory Items</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- KPI Cards Row 2 - Role-based display -->
            <div class="row row-deck row-cards mt-3">
                <?php if($role === 'student' || $role === 'staff'): ?>
                    <!-- My Pending Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-warning">
                                        <i class="fas fa-clock"></i> Pending
                                    </div>
                                    <h3 class="mb-0"><?= $pending_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">My Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Approved Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-success">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </div>
                                    <h3 class="mb-0"><?= $approved_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">My Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Completed Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-info">
                                        <i class="fas fa-check"></i> Completed
                                    </div>
                                    <h3 class="mb-0"><?= $completed_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">My Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Cancelled Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-danger">
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    </div>
                                    <h3 class="mb-0"><?= $cancelled_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">My Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Status -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card bg-info-light">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-info">
                                        <i class="fas fa-certificate"></i> Certificates
                                    </div>
                                    <h3 class="mb-0">
                                        <a href="<?= base_url('/certificate') ?>" class="text-info text-decoration-none">→</a>
                                    </h3>
                                    <p class="text-muted text-sm mt-2">View My Certs</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Announcements -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card bg-primary-light">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-primary">
                                        <i class="fas fa-bullhorn"></i> Announcements
                                    </div>
                                    <h3 class="mb-0">
                                        <a href="<?= base_url('/announcement') ?>" class="text-primary text-decoration-none">→</a>
                                    </h3>
                                    <p class="text-muted text-sm mt-2">Latest News</p>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif($role === 'nurse'): ?>
                    <!-- Pending Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-warning">
                                        <i class="fas fa-clock"></i> Pending
                                    </div>
                                    <h3 class="mb-0"><?= $pending_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-success">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </div>
                                    <h3 class="mb-0"><?= $approved_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Completed Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-info">
                                        <i class="fas fa-check"></i> Completed
                                    </div>
                                    <h3 class="mb-0"><?= $completed_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cancelled Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-danger">
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    </div>
                                    <h3 class="mb-0"><?= $cancelled_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Items -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card bg-danger-light">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> Low Stock
                                    </div>
                                    <h3 class="mb-0 text-danger"><?= $low_stock_items ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Items</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Consultations -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card bg-success-light">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-success">
                                        <i class="fas fa-stethoscope"></i> Consultations
                                    </div>
                                    <h3 class="mb-0 text-success"><?= $total_consultations ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Total</p>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Admin: Pending Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-warning">
                                        <i class="fas fa-clock"></i> Pending
                                    </div>
                                    <h3 class="mb-0"><?= $pending_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin: Approved Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-success">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </div>
                                    <h3 class="mb-0"><?= $approved_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin: Completed Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-info">
                                        <i class="fas fa-check"></i> Completed
                                    </div>
                                    <h3 class="mb-0"><?= $completed_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin: Cancelled Appointments -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-danger">
                                        <i class="fas fa-times-circle"></i> Cancelled
                                    </div>
                                    <h3 class="mb-0"><?= $cancelled_appointments ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Appointments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin: Low Stock Items -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card bg-danger-light">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-danger">
                                        <i class="fas fa-exclamation-triangle"></i> Low Stock
                                    </div>
                                    <h3 class="mb-0 text-danger"><?= $low_stock_items ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">Items</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin: Total Users -->
                    <div class="col-sm-6 col-lg-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="text-truncate">
                                    <div class="text-primary">
                                        <i class="fas fa-users"></i> Users
                                    </div>
                                    <h3 class="mb-0"><?= $total_users ?? 0 ?></h3>
                                    <p class="text-muted text-sm mt-2">In System</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Recent Activity Section - Role-based -->
            <div class="row row-deck row-cards mt-4">
                <?php if($role === 'student' || $role === 'staff'): ?>
                    <!-- My Recent Consultations -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-history"></i> My Recent Consultations
                                </h3>
                                <div class="card-options">
                                    <a href="<?= base_url('/consultation') ?>" class="btn btn-sm btn-primary">View All</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Diagnosis</th>
                                            <th>Treatment</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($recent_consultations)): ?>
                                            <?php foreach($recent_consultations as $consultation): ?>
                                                <tr>
                                                    <td class="text-truncate">
                                                        <?= substr($consultation['diagnosis'], 0, 30) ?>...
                                                    </td>
                                                    <td class="text-muted text-truncate">
                                                        <?= substr($consultation['treatment'] ?? 'N/A', 0, 25) ?>...
                                                    </td>
                                                    <td class="text-muted">
                                                        <?= date('M d, Y', strtotime($consultation['consultation_date'])) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    <i class="fas fa-inbox"></i> No consultations yet
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- My Recent Appointments -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-alt"></i> My Recent Appointments
                                </h3>
                                <div class="card-options">
                                    <a href="<?= base_url('/appointment') ?>" class="btn btn-sm btn-primary">View All</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Purpose</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($recent_appointments)): ?>
                                            <?php foreach($recent_appointments as $appointment): ?>
                                                <tr>
                                                    <td class="text-truncate">
                                                        <?= $appointment['purpose'] ?? 'General Checkup' ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            $statusClass = match($appointment['status']) {
                                                                'pending' => 'badge-warning',
                                                                'approved' => 'badge-success',
                                                                'completed' => 'badge-info',
                                                                'cancelled' => 'badge-danger',
                                                                default => 'badge-secondary'
                                                            };
                                                        ?>
                                                        <span class="badge <?= $statusClass ?>">
                                                            <?= ucfirst($appointment['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-muted">
                                                        <?= date('M d, Y', strtotime($appointment['appointment_date'])) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    <i class="fas fa-inbox"></i> No appointments yet
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <!-- Recent Consultations (Admin/Nurse) -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-history"></i> Recent Consultations
                                </h3>
                                <div class="card-options">
                                    <a href="<?= base_url('/consultation') ?>" class="btn btn-sm btn-primary">View All</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Patient</th>
                                            <th>Diagnosis</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($recent_consultations)): ?>
                                            <?php foreach($recent_consultations as $consultation): ?>
                                                <tr>
                                                    <td class="text-truncate">
                                                        Patient #<?= $consultation['patient_id'] ?>
                                                    </td>
                                                    <td class="text-muted text-truncate">
                                                        <?= substr($consultation['diagnosis'], 0, 30) ?>...
                                                    </td>
                                                    <td class="text-muted">
                                                        <?= date('M d, Y', strtotime($consultation['consultation_date'])) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    <i class="fas fa-inbox"></i> No consultations yet
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Appointments (Admin/Nurse) -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-calendar-alt"></i> Recent Appointments
                                </h3>
                                <div class="card-options">
                                    <a href="<?= base_url('/appointment') ?>" class="btn btn-sm btn-primary">View All</a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>Patient</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(!empty($recent_appointments)): ?>
                                            <?php foreach($recent_appointments as $appointment): ?>
                                                <tr>
                                                    <td class="text-truncate">
                                                        Patient #<?= $appointment['patient_id'] ?>
                                                    </td>
                                                    <td>
                                                        <?php 
                                                            $statusClass = match($appointment['status']) {
                                                                'pending' => 'badge-warning',
                                                                'approved' => 'badge-success',
                                                                'completed' => 'badge-info',
                                                                'cancelled' => 'badge-danger',
                                                                default => 'badge-secondary'
                                                            };
                                                        ?>
                                                        <span class="badge <?= $statusClass ?>">
                                                            <?= ucfirst($appointment['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-muted">
                                                        <?= date('M d, Y', strtotime($appointment['appointment_date'])) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    <i class="fas fa-inbox"></i> No appointments yet
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Actions Section -->
            <div class="row row-deck row-cards mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-auto">
                                    <a href="<?= base_url('/patient') ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-user-injured"></i> View Patients
                                    </a>
                                </div>
                                <?php if($role === 'student' || $role === 'staff'): ?>
                                    <div class="col-auto">
                                        <a href="<?= base_url('/patient/add') ?>" class="btn btn-outline-success">
                                            <i class="fas fa-user-plus"></i> Add Patient
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="col-auto">
                                    <a href="<?= base_url('/consultation') ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-stethoscope"></i> View Consultations
                                    </a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('/appointment') ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-calendar-check"></i> View Appointments
                                    </a>
                                </div>
                                <div class="col-auto">
                                    <a href="<?= base_url('/inventory') ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-boxes"></i> View Inventory
                                    </a>
                                </div>
                                <?php if($role === 'admin' || $role === 'nurse'): ?>
                                    <div class="col-auto">
                                        <a href="<?= base_url('/report') ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-file-alt"></i> Generate Report
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <a href="<?= base_url('/announcement') ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-bullhorn"></i> Announcements
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php if($role === 'admin'): ?>
                                    <div class="col-auto">
                                        <a href="<?= base_url('/list_user') ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-users"></i> Manage Users
                                        </a>
                                    </div>
                                    <div class="col-auto">
                                        <a href="<?= base_url('/certificate') ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-certificate"></i> Certificates
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?= $this->endSection() ?>