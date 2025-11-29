<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>

<?php if(!session()->get('hasPatient')): ?>
    <div class="alert alert-warning alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle"></i> You don't have patient info. <a href="<?= base_url('/patient/add') ?>" class="alert-link">Add</a> to continue.
    </div>
<?php endif; ?>

<br><br>
<div class="page-header d-print-none">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h2 class="page-title">
                <i class="fas fa-calendar-check"></i> Appointments
            </h2>
            <p class="text-muted">Manage your appointments</p>
        </div>
        <div class="col-auto">
            <?php if(session()->get('hasPatient')): ?>
                <a href="<?= base_url('/appointment/add') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Appointment
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<br>
<?php if(session()->get('role') === 'student' || session()->get('role') === 'staff'): ?>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">My Appointments</h3>
            </div>
            <form method="get" action="<?= base_url('/appointment') ?>" class="row g-3 mt-2">
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
                            <a href="<?= base_url('/appointment?sort=asc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'asc') ? 'btn-info' : 'btn-outline-info' ?>">
                                <i class="fas fa-arrow-up"></i> Ascending
                            </a>
                            <a href="<?= base_url('/appointment?sort=desc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'desc' || empty(request()->getGet('sort'))) ? 'btn-info' : 'btn-outline-info' ?>">
                                <i class="fas fa-arrow-down"></i> Descending
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient ID</th>
                        <th>Date</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($appoint)): ?>
                        <?php foreach($appoint as $a): ?>
                            <tr>
                                <td><span class="badge badge-primary"><?= esc($a['appointment_id']) ?></span></td>
                                <td><?= esc($a['patient_id']) ?></td>
                                <td><?= esc($a['appointment_date']) ?></td>
                                <td><?= esc($a['purpose']) ?></td>
                                <td><span class="badge badge-outline-secondary"><?= esc($a['status']) ?></span></td>
                                <td><?= (!empty($a['remarks'])) ? esc($a['remarks']) : '<span class="text-muted">—</span>' ?></td>
                                <td><?= esc($a['created_at']) ?></td>
                                <td>
                                    <a href="<?= base_url('/appointment/edit/' . $a['appointment_id']) ?>" class="btn btn-sm btn-ghost-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('/appointment/delete/' . $a['appointment_id']) ?>" class="btn btn-sm btn-ghost-danger" onclick="return confirm('Delete this appointment?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">You don't have any appointments yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php elseif(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>

    <div class="card mb-3">
        <div class="card-header">

            <form action="<?= base_url('/appointment') ?>" method="get" class="row g-3">
                <div class="col-12">
                    <div class="d-flex gap-3">
                        <div class="flex-grow-1">
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by ID, patient ID, purpose..." value="<?= esc(request()->getGet('search') ?? '') ?>">
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
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Filter by Status:</label>
                            <select name="status" class="form-select form-select-lg" onchange="this.form.submit()">
                                <option value="all" <?= (session()->get('appointment_status') === 'all') ? 'selected' : '' ?>>All Appointments</option>
                                <option value="pending" <?= (session()->get('appointment_status') === 'pending') ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= (session()->get('appointment_status') === 'approved') ? 'selected' : '' ?>>Approved</option>
                                <option value="cancelled" <?= (session()->get('appointment_status') === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                <option value="completed" <?= (session()->get('appointment_status') === 'completed') ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">Sort by ID:</label>
                            <div class="btn-group w-100" role="group">
                                <a href="<?= base_url('/appointment?sort=asc&status=' . (session()->get('appointment_status') ?? 'all') . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'asc') ? 'btn-info' : 'btn-outline-info' ?>">
                                    <i class="fas fa-arrow-up"></i> Ascending
                                </a>
                                <a href="<?= base_url('/appointment?sort=desc&status=' . (session()->get('appointment_status') ?? 'all') . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'desc' || empty(request()->getGet('sort'))) ? 'btn-info' : 'btn-outline-info' ?>">
                                    <i class="fas fa-arrow-down"></i> Descending
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if(!empty($appoint)): ?>
        <div class="row">
            <?php foreach($appoint as $a): ?>
                <div class="col-md-4">
                    <form action="<?= base_url('appointment/update/' . $a['appointment_id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <?php session()->set('activity', 'save') ?>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">Appointment <span class="badge badge-primary"><?= esc($a['appointment_id']) ?></span></h3>
                                <span class="badge badge-outline-secondary"><?= esc($a['appointment_date']) ?></span>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Patient ID:</strong> <?= esc($a['patient_id']) ?></p>
                                        <p><strong>Purpose:</strong> <?= esc($a['purpose']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Status</strong></label>
                                            <select name="status" class="form-select">
                                                <option value="pending" <?= ($a['status'] === 'pending') ? 'selected' : '' ?>>Pending</option>
                                                <option value="approved" <?= ($a['status'] === 'approved') ? 'selected' : '' ?>>Approved</option>
                                                <option value="cancelled" <?= ($a['status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                                                <option value="completed" <?= ($a['status'] === 'completed') ? 'selected' : '' ?>>Completed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Remarks</strong></label>
                                    <input type="text" name="remarks" class="form-control" value="<?= (!empty($a['remarks'])) ? esc($a['remarks']) : '' ?>">
                                </div>
                                <p><strong>Created:</strong> <?= esc($a['created_at']) ?></p>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save
                                </button>
                                <a href="<?= base_url('/appointment/edit/' . $a['appointment_id']) ?>" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= base_url('/appointment/delete/' . $a['appointment_id']) ?>" class="btn btn-danger" onclick="return confirm('Delete this appointment?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty">
            <div class="empty-header"><i class="fas fa-inbox"></i></div>
            <p class="empty-title">No <?= session()->get('appointment_status') ?> Appointments</p>
            <p class="empty-subtitle">No appointments found with the selected status</p>
        </div>
    <?php endif; ?>

<?php endif; ?>

<?= $this->endSection() ?>