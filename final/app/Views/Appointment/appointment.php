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

<?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="<?= base_url('/appointment?status=all' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '') . (request()->getGet('sort') ? '&sort=' . esc(request()->getGet('sort')) : '')) ?>" class="nav-link <?= (request()->getGet('status') ?? 'all') === 'all' ? 'active' : '' ?>">All</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="<?= base_url('/appointment?status=pending' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '') . (request()->getGet('sort') ? '&sort=' . esc(request()->getGet('sort')) : '')) ?>" class="nav-link <?= (request()->getGet('status') ?? 'all') === 'pending' ? 'active' : '' ?>">Pending</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="<?= base_url('/appointment?status=approved' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '') . (request()->getGet('sort') ? '&sort=' . esc(request()->getGet('sort')) : '')) ?>" class="nav-link <?= (request()->getGet('status') ?? 'all') === 'approved' ? 'active' : '' ?>">Approved</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="<?= base_url('/appointment?status=rejected' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '') . (request()->getGet('sort') ? '&sort=' . esc(request()->getGet('sort')) : '')) ?>" class="nav-link <?= (request()->getGet('status') ?? 'all') === 'rejected' ? 'active' : '' ?>">Rejected</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="<?= base_url('/appointment?status=completed' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '') . (request()->getGet('sort') ? '&sort=' . esc(request()->getGet('sort')) : '')) ?>" class="nav-link <?= (request()->getGet('status') ?? 'all') === 'completed' ? 'active' : '' ?>">Completed</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <br>
<?php endif; ?>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Appointment Date</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($appoint)): ?>
                    <?php foreach($appoint as $a): ?>
                        <tr>
                            <td><span class="badge badge-primary"><?= esc($a['appointment_id']) ?></span></td>
                            <td><?= esc($a['patient_name'] ?? 'N/A') ?></td>
                            <td><?= esc($a['appointment_date']) ?></td>
                            <td><?= esc($a['purpose'] ?? 'N/A') ?></td>
                            <td>
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
                            </td>
                            <td>
                                <a href="<?= base_url('/appointment/edit/' . $a['appointment_id']) ?>" class="btn btn-sm btn-ghost-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= base_url('/appointment/delete/' . $a['appointment_id']) ?>" class="btn btn-sm btn-ghost-danger" onclick="return confirm('Delete this appointment?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No appointments found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>