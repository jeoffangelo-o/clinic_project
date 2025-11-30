<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>

<br><br>
<div class="page-header d-print-none">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h2 class="page-title">
                <i class="fas fa-certificate"></i> Medical Certificates
            </h2>
            <p class="text-muted">Manage medical certificates</p>
        </div>
        <div class="col-auto">
            <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>
                <a href="<?= base_url('/certificate/add') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Certificate
                </a>
            <?php endif; ?>
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
</div>
<br>

<div class="card">
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Certificate ID</th>
                    <th>Patient ID</th>
                    <th>Type</th>
                    <th>Validity Start</th>
                    <th>Validity End</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($certificate)): ?>
                    <?php foreach($certificate as $c): ?>
                        <tr>
                            <td><span class="badge badge-primary">#<?= esc($c['certificate_id']) ?></span></td>
                            <td><?= esc($c['patient_id']) ?></td>
                            <td><span class="badge badge-outline-secondary"><?= esc(ucfirst(str_replace('_', ' ', $c['certificate_type']))) ?></span></td>
                            <td><?= esc($c['validity_start'] ?: 'N/A') ?></td>
                            <td><?= esc($c['validity_end'] ?: 'N/A') ?></td>
                            <td>
                                <a href="<?= base_url('/certificate/view/' . $c['certificate_id']) ?>" class="btn btn-sm btn-ghost-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>
                                    <a href="<?= base_url('/certificate/edit/' . $c['certificate_id']) ?>" class="btn btn-sm btn-ghost-secondary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="<?= base_url('/certificate/delete/' . $c['certificate_id']) ?>" class="btn btn-sm btn-ghost-danger" onclick="return confirm('Delete certificate #<?= esc($c['certificate_id']) ?>')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No certificates found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
