<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>

<br><br>
<div class="page-header d-print-none">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h2 class="page-title">
                <i class="fas fa-calendar-check"></i> Appointments
            </h2>
            <p class="text-muted">Manage appointments</p>
        </div>
        <div class="col-auto">
            <?php if(session()->get('role') === 'student' || session()->get('role') === 'staff'): ?>
                <?php if(session()->get('hasPatient')): ?>
                    <a href="<?= base_url('/appointment/add') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Appointment
                    </a>
                <?php endif; ?>
            <?php elseif(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>
                <a href="<?= base_url('/appointment/add') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Appointment
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<br>

<?php if(session()->get('role') === 'student' || session()->get('role') === 'staff'): ?>
    <?php if(!session()->get('hasPatient')): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle"></i> You don't have patient info. <a href="<?= base_url('/patient/add') ?>" class="alert-link">Add</a> to continue.
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <form method="get" action="<?= base_url('/appointment') ?>" class="row g-3">
            <div class="col-12">
                <div class="d-flex gap-3">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by ID, patient ID, purpose, status..." value="<?= esc(request()->getGet('search') ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if(request()->getGet('search')): ?>
                        <a href="<?= base_url('/appointment') ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex gap-2 align-items-center">
                    <span class="text-dark fw-bold">Sort by ID:</span>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('/appointment?sort=asc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '') . (request()->getGet('status') && request()->getGet('status') !== 'all' ? '&status=' . esc(request()->getGet('status')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'asc') ? 'btn-info' : 'btn-outline-info' ?>">
                            <i class="fas fa-arrow-up"></i> Ascending
                        </a>
                        <a href="<?= base_url('/appointment?sort=desc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '') . (request()->getGet('status') && request()->getGet('status') !== 'all' ? '&status=' . esc(request()->getGet('status')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'desc' || empty(request()->getGet('sort'))) ? 'btn-info' : 'btn-outline-info' ?>">
                            <i class="fas fa-arrow-down"></i> Descending
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<br>

<?php if(!empty($appoint)): ?>
    <div class="row">
        <?php foreach($appoint as $a): ?>
            <div class="col-md-4">
                <div class="card mb-3 h-100">
                    <div class="card-header">
                        <h3 class="card-title">Appointment #<?= esc($a['appointment_id']) ?></h3>
                        <div class="card-options">
                            <?php
                                $statusClass = 'secondary';
                                if($a['status'] === 'pending') {
                                    $statusClass = 'warning';
                                } else if($a['status'] === 'approved') {
                                    $statusClass = 'success';
                                } else if($a['status'] === 'rejected') {
                                    $statusClass = 'danger';
                                } else if($a['status'] === 'completed') {
                                    $statusClass = 'info';
                                }
                            ?>
                            <span class="badge badge-<?= $statusClass ?>"><?= ucfirst(esc($a['status'])) ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-secondary mb-2"><small><i class="fas fa-user"></i> <strong><?= esc($a['patient_name'] ?? 'N/A') ?></strong></small></p>
                        <p class="text-secondary mb-2"><small><i class="fas fa-calendar"></i> <?= esc($a['appointment_date']) ?></small></p>
                        <p class="text-secondary mb-3"><small><i class="fas fa-stethoscope"></i> <?= esc($a['purpose'] ?? 'N/A') ?></small></p>
                        
                        <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>
                            <form method="post" action="<?= base_url('/appointment/update/' . $a['appointment_id']) ?>" class="mb-3">
                                <?= csrf_field() ?>
                                <input type="hidden" name="activity" value="save">
                                <div class="d-flex gap-2 align-items-center">
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="pending" <?= esc($a['status']) === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="approved" <?= esc($a['status']) === 'approved' ? 'selected' : '' ?>>Approved</option>
                                        <option value="rejected" <?= esc($a['status']) === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                        <option value="completed" <?= esc($a['status']) === 'completed' ? 'selected' : '' ?>>Completed</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary" title="Save status">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer text-end">
                        <a href="<?= base_url('/appointment/edit/' . $a['appointment_id']) ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="<?= base_url('/appointment/delete/' . $a['appointment_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this appointment?')">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty">
        <div class="empty-header"><i class="fas fa-calendar"></i></div>
        <p class="empty-title">No Appointments Found</p>
        <p class="empty-subtitle">Check back later or create a new appointment</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>