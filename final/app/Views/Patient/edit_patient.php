<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>

<div class="page-header d-print-none">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Edit Patient</h2>
        </div>
        <div class="col-auto">
            <a href="/patient" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>

<?php if(session()->getFlashData('message')): ?>
    <div data-flash-message="success" style="display: none;"><?= session()->getFlashData('message') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('/patient/update/'.$p['patient_id']) ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" value="<?= $p['first_name']?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control" value="<?= $p['middle_name']?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control" value="<?= $p['last_name']?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select" required>
                                    <option value="male" <?= ($p['gender'] === 'male') ? 'selected' : '' ?>>Male</option>
                                    <option value="female" <?= ($p['gender'] === 'female') ? 'selected' : '' ?>>Female</option>
                                    <option value="other" <?= ($p['gender'] === 'other') ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Birth Date <span class="text-danger">*</span></label>
                                <input type="date" name="birth_date" class="form-control" value="<?= $p['birth_date']?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Blood Type <span class="text-danger">*</span></label>
                                <select name="blood_type" class="form-select" required>
                                    <option value="">-- Select Blood Type --</option>
                                    <option value="A+" <?= ($p['blood_type'] === 'A+') ? 'selected' : '' ?>>A+</option>
                                    <option value="A-" <?= ($p['blood_type'] === 'A-') ? 'selected' : '' ?>>A-</option>
                                    <option value="B+" <?= ($p['blood_type'] === 'B+') ? 'selected' : '' ?>>B+</option>
                                    <option value="B-" <?= ($p['blood_type'] === 'B-') ? 'selected' : '' ?>>B-</option>
                                    <option value="AB+" <?= ($p['blood_type'] === 'AB+') ? 'selected' : '' ?>>AB+</option>
                                    <option value="AB-" <?= ($p['blood_type'] === 'AB-') ? 'selected' : '' ?>>AB-</option>
                                    <option value="O+" <?= ($p['blood_type'] === 'O+') ? 'selected' : '' ?>>O+</option>
                                    <option value="O-" <?= ($p['blood_type'] === 'O-') ? 'selected' : '' ?>>O-</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                        <input type="text" name="contact_no" class="form-control" value="<?= $p['contact_no']?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea name="address" class="form-control" rows="2" required><?= $p['address']?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Allergies</label>
                        <textarea name="allergies" class="form-control" rows="2"><?= $p['allergies']?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Medical History</label>
                        <textarea name="medical_history" class="form-control" rows="3"><?= $p['medical_history']?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Emergency Contact Number</label>
                        <input type="text" name="emergency_contact" class="form-control" value="<?= $p['emergency_contact']?>">
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Update Patient
                        </button>
                        <a href="/patient" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>