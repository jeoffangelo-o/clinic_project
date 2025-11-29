<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>

<br><br>
<div class="page-header d-print-none">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h2 class="page-title">
                <i class="fas fa-stethoscope"></i> Consultations
            </h2>
            <p class="text-muted">View and manage patient consultations</p>
        </div>
        <div class="col-auto">
            <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>
                <a href="<?= base_url('/consultation/add') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Consultation
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<br>
<div class="card">
    <div class="card-header">
        <form method="get" action="<?= base_url('/consultation') ?>" class="row g-3">
            <div class="col-12">
                <div class="d-flex gap-3">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by consultation ID, patient ID, nurse ID..." value="<?= esc(request()->getGet('search') ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if(request()->getGet('search')): ?>
                        <a href="<?= base_url('/consultation') ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex gap-2 align-items-center">
                    <span class="text-dark fw-bold">Sort by ID:</span>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('/consultation?sort=asc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'asc') ? 'btn-info' : 'btn-outline-info' ?>">
                            <i class="fas fa-arrow-up"></i> Ascending
                        </a>
                        <a href="<?= base_url('/consultation?sort=desc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'desc' || empty(request()->getGet('sort'))) ? 'btn-info' : 'btn-outline-info' ?>">
                            <i class="fas fa-arrow-down"></i> Descending
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<br>
<?php if(!empty($consult)): ?>
    <div class="row">
        <?php foreach($consult as $c): ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Consultation ID: <span class="badge badge-primary"><?= esc($c['consultation_id']) ?></span></h3>
                        <div class="card-options">
                            <span class="badge badge-outline-secondary"><?= esc($c['consultation_date']) ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Appointment ID:</strong> <?= ($c['appointment_id'] !== null) ? esc($c['appointment_id']) : '<span class="badge badge-secondary">N/A</span>' ?></p>
                                <p><strong>Patient ID:</strong> <?= esc($c['patient_id']) ?></p>
                                <p><strong>Nurse ID:</strong> <?= esc($c['nurse_id']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Diagnosis:</strong> <?= esc($c['diagnosis']) ?></p>
                                <p><strong>Treatment:</strong> <?= esc($c['treatment']) ?></p>
                                <p><strong>Prescription:</strong> <?= esc($c['prescription']) ?></p>
                            </div>
                        </div>
                        <p><strong>Notes:</strong> <?= ($c['notes'] === null || $c['notes'] === '') ? '<span class="text-muted">None</span>' : esc($c['notes']) ?></p>
                        
                        <?php if(!empty($c['medicines'])): ?>
                            <div class="mt-3">
                                <strong>Medicines Used:</strong>
                                <ul class="list-unstyled">
                                    <?php foreach($c['medicines'] as $med): ?>
                                        <li><i class="fas fa-capsules"></i> <?= esc($med['item_name']) ?> - <?= esc($med['quantity_used']) ?> <?= esc($med['unit']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php else: ?>
                            <p><strong>Medicines Used:</strong> <span class="text-muted">None</span></p>
                        <?php endif; ?>
                    </div>
                    <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>
                        <div class="card-footer text-end">
                            <a href="<?= base_url('/consultation/edit/' . $c['consultation_id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?= base_url('/consultation/delete/' . $c['consultation_id']) ?>" class="btn btn-danger" onclick="return confirm('Delete this consultation?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty">
        <div class="empty-header"><i class="fas fa-inbox"></i></div>
        <p class="empty-title">No Consultations Yet</p>
        <p class="empty-subtitle">Start by creating a new consultation</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
</body>
</html>