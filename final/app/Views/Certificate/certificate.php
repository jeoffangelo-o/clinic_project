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
<br>
<div class="card">
    <div class="card-header">
        <form method="get" action="<?= base_url('/certificate') ?>" class="row g-3">
            <div class="col-12">
                <div class="d-flex gap-3">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by certificate ID, patient ID, type..." value="<?= esc(request()->getGet('search') ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if(request()->getGet('search')): ?>
                        <a href="<?= base_url('/certificate') ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex gap-2 align-items-center">
                    <span class="text-dark fw-bold">Sort by ID:</span>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('/certificate?sort=asc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'asc') ? 'btn-info' : 'btn-outline-info' ?>">
                            <i class="fas fa-arrow-up"></i> Ascending
                        </a>
                        <a href="<?= base_url('/certificate?sort=desc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'desc' || empty(request()->getGet('sort'))) ? 'btn-info' : 'btn-outline-info' ?>">
                            <i class="fas fa-arrow-down"></i> Descending
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
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
                                <span class="badge bg-primary text-white">
                                    <?= esc(ucfirst(str_replace('_', ' ', $c['certificate_type']))) ?>
                                </span>
                            </td>
                            <td>
                                <small class="text-dark">
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
