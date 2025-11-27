<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>
<br><br>
<div class="page-header d-print-none">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h2 class="page-title">
                <i class="fas fa-users"></i> Manage Patients
            </h2>
            <p class="text-muted">Manage patient records and information</p>
        </div>
        <div class="col-auto">
            <a href="<?= base_url('/patient/add') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Patient
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>User ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($patient)): ?>
                    <?php foreach($patient as $p): ?>
                        <tr>
                            <td><span class="badge badge-primary"><?= esc($p['patient_id']) ?></span></td>
                            <td><?= (!empty($p['user_id'])) ? esc($p['user_id']) : '<span class="badge badge-secondary">Walk In</span>' ?></td>
                            <td><?= esc($p['last_name']) . ', ' . esc($p['first_name']) . ' ' . esc($p['middle_name']) ?></td>
                            <td><?= esc($p['gender']) ?></td>
                            <td><?= esc($p['created_at']) ?></td>
                            <td>
                                <a href="<?= base_url('/patient/view/' . $p['patient_id']) ?>" class="btn btn-sm btn-ghost-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="<?= base_url('/patient/edit/' . $p['patient_id']) ?>" class="btn btn-sm btn-ghost-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= base_url('/patient/delete/' . $p['patient_id']) ?>" class="btn btn-sm btn-ghost-danger" onclick="return confirm('Are you sure to delete patient #<?= esc($p['patient_id']) ?>')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No patients found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
</body>
</html>