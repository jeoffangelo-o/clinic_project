<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>
<br><br>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Patient Information</h2>
        </div>
        <div class="col-auto">
            <a href="/patient" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>
<br>
<div class="card">
    <div class="card-body">
        <div class="row mb-3">
                    <div class="col-md-6">
                        <h5 class="text-dark fw-bold mb-1">Patient ID</h5>
                        <p class="mb-0"><span class="badge bg-blue">#<?= esc($p['patient_id'])?></span></p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-dark fw-bold mb-1">User ID</h5>
                        <p class="mb-0"><?= ($p['user_id'] !== null) ? '<span class="badge bg-primary text-white">' . esc($p['user_id']) . '</span>' : '<span class="badge bg-warning text-dark">Walk-In</span>' ?></p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-dark fw-bold mb-1">First Name</h5>
                        <p class="mb-3"><?= esc($p['first_name'])?></p>

                        <h5 class="text-dark fw-bold mb-1">Middle Name</h5>
                        <p class="mb-3"><?= !empty($p['middle_name']) ? esc($p['middle_name']) : '<span class="text-muted">N/A</span>' ?></p>

                        <h5 class="text-dark fw-bold mb-1">Last Name</h5>
                        <p class="mb-3"><?= esc($p['last_name'])?></p>
                    </div>

                    <div class="col-md-6">
                        <h5 class="text-dark fw-bold mb-1">Gender</h5>
                        <p class="mb-3">
                            <span class="badge bg-primary text-white"><?= ucfirst(esc($p['gender']))?></span>
                        </p>

                        <h5 class="text-dark fw-bold mb-1">Birth Date</h5>
                        <p class="mb-3"><?= esc($p['birth_date'] ?? 'N/A') ?></p>

                        <h5 class="text-dark fw-bold mb-1">Blood Type</h5>
                        <p class="mb-3">
                            <span class="badge bg-danger"><?= esc($p['blood_type'])?></span>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-dark fw-bold mb-1">Contact Number</h5>
                        <p class="mb-3"><?= esc($p['contact_no'])?></p>

                        <h5 class="text-dark fw-bold mb-1">Address</h5>
                        <p class="mb-3"><?= esc($p['address'])?></p>
                    </div>

                    <div class="col-md-6">
                        <h5 class="text-dark fw-bold mb-1">Emergency Contact</h5>
                        <p class="mb-3"><?= esc($p['emergency_contact'])?></p>

                        <h5 class="text-dark fw-bold mb-1">Date Added</h5>
                        <p class="mb-3"><?= esc($p['created_at'])?></p>
                    </div>
                </div>

                <hr>

                <div>
                    <h5 class="text-dark fw-bold mb-2">Allergies</h5>
                    <p class="mb-3"><?= !empty($p['allergies']) ? '<span class="badge bg-warning">' . esc($p['allergies']) . '</span>' : '<span class="text-muted">None</span>' ?></p>

                    <h5 class="text-dark fw-bold mb-2">Medical History</h5>
                    <p class="mb-0"><?= !empty($p['medical_history']) ? esc($p['medical_history']) : '<span class="text-muted">None</span>' ?></p>
                </div>

                <hr>

                <div class="form-footer">
                    <a href="<?= base_url('/patient/edit/' . $p['patient_id']) ?>" class="btn btn-primary">
                        <i class="fa-solid fa-pencil"></i> Edit Patient
                    </a>
                    <a href="/patient" class="btn btn-link">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>