<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>

<div class="page-header d-print-none">
    <h2 class="page-title">
        <i class="fas fa-calendar-plus"></i> Create Appointment
    </h2>
    <p class="text-muted">Schedule a new appointment</p>
</div>

<?php if(session()->getFlashData('message')): ?>
    <div data-flash-message="info" style="display: none;"><?= session()->getFlashData('message') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('/appointment/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Appointment Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="appointment_date" class="form-control" min="<?= date('Y-m-d\TH:i') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Purpose <span class="text-danger">*</span></label>
                        <textarea name="purpose" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Appointment
                        </button>
                        <a href="<?= base_url('/appointment') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>