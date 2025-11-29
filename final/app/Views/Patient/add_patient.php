<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>

<br><br>
<div class="page-header d-print-none">
    <h2 class="page-title">
        <i class="fas fa-user-plus"></i> Add Patient
    </h2>
    <p class="text-muted">Register a new patient</p>
</div>
<br>
<div class="card">
    <div class="card-body">
                <form action="<?= base_url('/patient/store') ?>" method="post">
                    <?= csrf_field() ?>

                    <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>
                        <div class="mb-3">
                            <label class="form-label" for="user_id">User ID (Optional)</label>
                            <input type="number" name="user_id" id="user_id" class="form-control" placeholder="Leave blank if none">
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="user_id" value="<?= session()->get('user_id') ?>">
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Birth Date <span class="text-danger">*</span></label>
                                <input type="date" name="birth_date" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Blood Type <span class="text-danger">*</span></label>
                                <select name="blood_type" class="form-select" required>
                                    <option value="">-- Select --</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="contact_no" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Allergies</label>
                        <textarea name="allergies" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Medical History</label>
                        <textarea name="medical_history" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Emergency Contact Number</label>
                        <input type="text" name="emergency_contact" class="form-control">
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Add Patient
                        </button>
                        <a href="<?= base_url('/patient') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>