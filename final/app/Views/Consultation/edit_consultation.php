<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>

<div class="page-header d-print-none">
    <h2 class="page-title">Edit Consultation</h2>
</div>

<?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'):  ?>

    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('/consultation/update/'.$consult['consultation_id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <small class="text-muted">Appointment ID</small>
                                    <p class="mb-0"><strong><?= ( $consult['appointment_id'] === null )? 'N/A' :  $consult['appointment_id']  ?></strong></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <small class="text-muted">Patient ID</small>
                                    <p class="mb-0"><strong><?=  $consult['patient_id'] ?></strong></p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                            <input type="text" name="diagnosis" class="form-control" value="<?=  $consult['diagnosis'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Treatment <span class="text-danger">*</span></label>
                            <input type="text" name="treatment" class="form-control" value="<?=  $consult['treatment'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prescription <span class="text-danger">*</span></label>
                            <input type="text" name="prescription" class="form-control" value="<?=  $consult['prescription'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"><?=  $consult['notes'] ?></textarea>
                        </div>

                        <!-- Medicines Section -->
                        <div class="card bg-light mt-4 mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-solid fa-boxes-stacked text-primary"></i>
                                    <h5 class="card-title mb-0 ms-2">Medicines Used</h5>
                                </div>
                                
                                <?php if(!empty($medicines)): ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach($medicines as $med): ?>
                                            <div class="list-group-item px-0 py-2">
                                                <div class="row align-items-center">
                                                    <div class="col">
                                                        <strong><?= esc($med['item_name']) ?></strong>
                                                    </div>
                                                    <div class="col-auto">
                                                        <span class="badge bg-secondary"><?= esc($med['quantity_used']) ?> <?= esc($med['unit']) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mb-0"><i class="fa-solid fa-circle-info"></i> No medicines allocated</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-check"></i> Update Consultation
                            </button>
                            <a href="/consultation" class="btn btn-link">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="alert alert-warning">
        <i class="fa-solid fa-circle-info"></i> Only nurses and administrators can edit consultations.
    </div>
<?php endif; ?>

<?= $this->endSection() ?>