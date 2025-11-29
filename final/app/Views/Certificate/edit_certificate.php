<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>
<br><br>
<div class="page-header d-print-none">
    <h2 class="page-title">Edit Medical Certificate</h2>
</div>
<br>
<?php if(!empty($cert)): ?>

<div class="card">
    <div class="card-body">
        <form action="/certificate/update/<?= $cert['certificate_id'] ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Patient ID <span class="text-danger">*</span></label>
                        <input type="number" name="patient_id" class="form-control" value="<?= $cert['patient_id'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Consultation ID</label>
                        <input type="number" name="consultation_id" class="form-control" value="<?= $cert['consultation_id'] ?: '' ?>">
                        <small class="text-muted">Optional - reference to consultation</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Certificate Type <span class="text-danger">*</span></label>
                        <select name="certificate_type" class="form-select" required>
                            <option value="fit_to_study" <?= $cert['certificate_type'] == 'fit_to_study' ? 'selected' : '' ?>>Fit to Study</option>
                            <option value="medical_leave" <?= $cert['certificate_type'] == 'medical_leave' ? 'selected' : '' ?>>Medical Leave</option>
                            <option value="injury_report" <?= $cert['certificate_type'] == 'injury_report' ? 'selected' : '' ?>>Injury Report</option>
                            <option value="others" <?= $cert['certificate_type'] == 'others' ? 'selected' : '' ?>>Others</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Diagnosis Summary</label>
                        <textarea name="diagnosis_summary" class="form-control" rows="3"><?= $cert['diagnosis_summary'] ?: '' ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Recommendation</label>
                        <textarea name="recommendation" class="form-control" rows="3"><?= $cert['recommendation'] ?: '' ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Validity Start Date</label>
                                <input type="date" name="validity_start" class="form-control" value="<?= $cert['validity_start'] ?: '' ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Validity End Date</label>
                                <input type="date" name="validity_end" class="form-control" value="<?= $cert['validity_end'] ?: '' ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Update Certificate
                        </button>
                        <a href="/certificate" class="btn btn-link">Cancel</a>
                    </div>
                </form>
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
