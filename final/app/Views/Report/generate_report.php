<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>
<br><br>
<div class="page-header d-print-none">
    <h2 class="page-title">Generate Report</h2>
</div>
<br>
<div class="card">
    <div class="card-body">
        <form action="/report/store" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Report Type <span class="text-danger">*</span></label>
                        <select name="report_type" class="form-select" required>
                            <option value="">-- Select Report Type --</option>
                            <option value="patient">Patient Report</option>
                            <option value="consultation">Consultation Report</option>
                            <option value="appointment">Appointment Report</option>
                            <option value="inventory">Inventory Report</option>
                            <option value="announcement">Announcement Report</option>
                            <option value="comprehensive">Comprehensive Report (All Data)</option>
                        </select>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-file-pdf"></i> Generate Report
                        </button>
                        <a href="/report" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
