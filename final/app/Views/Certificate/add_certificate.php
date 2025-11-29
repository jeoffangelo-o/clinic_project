<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>
<br><br>
<div class="page-header d-print-none">
    <h2 class="page-title">Add Certificate</h2>
</div>
<br>
<div class="card">
    <div class="card-body">
        <form action="/certificate/store" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Patient ID <span class="text-danger">*</span></label>
                        <input type="number" name="patient_id" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Consultation ID</label>
                        <input type="number" name="consultation_id" class="form-control">
                        <small class="text-muted">Optional - reference to consultation</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Certificate Type <span class="text-danger">*</span></label>
                        <select name="certificate_type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="fit_to_study">Fit to Study</option>
                            <option value="medical_leave">Medical Leave</option>
                            <option value="injury_report">Injury Report</option>
                            <option value="others">Others</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Diagnosis Summary</label>
                        <textarea name="diagnosis_summary" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Recommendation</label>
                        <textarea name="recommendation" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Validity Start Date</label>
                                <input type="date" name="validity_start" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Validity End Date</label>
                                <input type="date" name="validity_end" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Add Certificate
                        </button>
                        <a href="/certificate" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
