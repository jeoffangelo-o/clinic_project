<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>

<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Certificate Details</h2>
        </div>
        <div class="col-auto">
            <a href="/certificate/export-pdf/<?= $cert['certificate_id'] ?>" class="btn btn-primary" title="Export to PDF">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="/certificate" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<?php if(!empty($cert)): ?>

<div class="card">
    <div class="card-body">
        <div class="row mb-3">
                    <div class="col-md-6">
                        <h5 class="text-dark fw-bold mb-1">Certificate ID</h5>
                        <p class="mb-0"><span class="badge bg-blue text-white">#<?= esc($cert['certificate_id']) ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-dark fw-bold mb-1">Patient ID</h5>
                        <p class="mb-0"><?= esc($cert['patient_id']) ?></p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-dark fw-bold mb-1">Consultation ID</h5>
                        <p class="mb-3"><?= esc($cert['consultation_id'] ?: 'N/A') ?></p>

                        <h5 class="text-dark fw-bold mb-1">Certificate Type</h5>
                        <p class="mb-3">
                            <span class="badge bg-primary text-white"><?= esc(ucfirst(str_replace('_', ' ', $cert['certificate_type']))) ?></span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-dark fw-bold mb-1">Issued Date</h5>
                        <p class="mb-3"><?= esc($cert['issued_date']) ?></p>

                        <h5 class="text-dark fw-bold mb-1">Validity Period</h5>
                        <p class="mb-3">
                            <small class="text-dark">
                                <?= esc($cert['validity_start'] ?: 'N/A') ?> to <?= esc($cert['validity_end'] ?: 'N/A') ?>
                            </small>
                        </p>
                    </div>
                </div>

                <hr>

                <h5 class="text-dark fw-bold mb-2">Diagnosis Summary</h5>
                <p class="mb-3"><?= esc($cert['diagnosis_summary'] ?: 'N/A') ?></p>

                <h5 class="text-dark fw-bold mb-2">Recommendation</h5>
                <p class="mb-0"><?= esc($cert['recommendation'] ?: 'N/A') ?></p>

                <hr>

                <div class="form-footer">
                    <a href="<?= base_url('/certificate/edit/'.$cert['certificate_id']) ?>" class="btn btn-primary">
                        <i class="fa-solid fa-pencil"></i> Edit Certificate
                    </a>
                    <a href="<?= base_url('/certificate/delete/'.$cert['certificate_id']) ?>" class="btn btn-danger" onclick="return confirm('Delete this certificate?')">
                        <i class="fa-solid fa-trash"></i> Delete
                    </a>
                    <a href="/certificate" class="btn btn-link">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
    <div class="alert alert-warning">
        <i class="fa-solid fa-circle-exclamation"></i> Certificate not found
    </div>
    <a href="/certificate" class="btn btn-secondary">Back to Certificates</a>
<?php endif; ?>

<?= $this->endSection() ?>
