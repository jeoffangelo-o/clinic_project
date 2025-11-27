<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>

<div class="page-header d-print-none">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Edit Appointment</h2>
        </div>
        <div class="col-auto">
            <a href="/appointment" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<?php if(session()->getFlashData('message')): ?>
    <div data-flash-message="success" style="display: none;"><?= session()->getFlashData('message') ?></div>
<?php endif; ?>

<?php session()->set('activity', 'edit') ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('appointment/update/'.$a['appointment_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Appointment Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="appointment_date" class="form-control" min="<?= date('Y-m-d\TH:i') ?>" value="<?= esc($a['appointment_date']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Purpose <span class="text-danger">*</span></label>
                        <textarea name="purpose" class="form-control" rows="3" required><?= esc($a['purpose']) ?></textarea>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Update Appointment
                        </button>
                        <a href="/appointment" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>