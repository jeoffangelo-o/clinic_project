<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>
<br><br>
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Medical Certificates</h2>
        </div>
        <div class="col-auto">
            <a href="/certificate/add" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add Certificate
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Certificate ID</th>
                    <th>Patient ID</th>
                    <th>Type</th>
                    <th>Validity</th>
                    <th>Issued Date</th>
                    <th class="w-1">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($certificate)): ?>
                    <?php foreach($certificate as $c): ?>
                        <tr>
                            <td>
                                <span class="badge bg-blue text-white">#<?= esc($c['certificate_id']) ?></span>
                            </td>
                            <td><?= esc($c['patient_id']) ?></td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?= esc(ucfirst(str_replace('_', ' ', $c['certificate_type']))) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?= esc($c['validity_start'] ?: 'N/A') ?> to <?= esc($c['validity_end'] ?: 'N/A') ?>
                                </small>
                            </td>
                            <td>
                                <small class="text-muted"><?= esc($c['issued_date']) ?></small>
                            </td>
                            <td class="text-end">
                                <div style="display: flex; gap: 12px; justify-content: flex-end; align-items: center;">
                                    <a href="<?= base_url('/certificate/view/'.$c['certificate_id'])?>" style="color: #206bc4; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 4px;" title="View">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>
                                    <a href="<?= base_url('/certificate/edit/'.$c['certificate_id'])?>" style="color: #206bc4; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 4px;" title="Edit">
                                        <i class="fa-solid fa-pencil"></i> Edit
                                    </a>
                                    <a href="<?= base_url('/certificate/delete/'.$c['certificate_id'])?>" style="color: #d63939; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 4px;" title="Delete" onclick="return confirm('Delete certificate #<?= esc($c['certificate_id']) ?>?')">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty">
                                <div class="empty-img">
                                    <i class="fa-solid fa-file-medical text-muted" style="font-size: 48px;"></i>
                                </div>
                                <p class="empty-title">No Certificates</p>
                                <p class="empty-subtitle">No medical certificates have been created yet.</p>
                                <div class="empty-action">
                                    <a href="/certificate/add" class="btn btn-primary">
                                        <i class="fa-solid fa-plus"></i> Add First Certificate
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
