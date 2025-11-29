<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>

<br><br>
<div class="page-header d-print-none">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h2 class="page-title">
                <i class="fas fa-boxes"></i> Manage Inventory
            </h2>
            <p class="text-muted">Track and manage medical supplies</p>
        </div>
        <div class="col-auto">
            <a href="<?= base_url('/inventory/add') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Item
            </a>
        </div>
    </div>
</div>
<br>
<div class="card">
    <div class="card-header">
        <form method="get" action="<?= base_url('/inventory') ?>" class="row g-3">
            <div class="col-12">
                <div class="d-flex gap-3">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by item name, ID, category..." value="<?= esc(request()->getGet('search') ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if(request()->getGet('search')): ?>
                        <a href="<?= base_url('/inventory') ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex gap-2 align-items-center">
                    <span class="text-dark fw-bold">Sort by ID:</span>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('/inventory?sort=asc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'asc') ? 'btn-info' : 'btn-outline-info' ?>">
                            <i class="fas fa-arrow-up"></i> Ascending
                        </a>
                        <a href="<?= base_url('/inventory?sort=desc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'desc' || empty(request()->getGet('sort'))) ? 'btn-info' : 'btn-outline-info' ?>">
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
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Expiry Date</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($inventory)): ?>
                    <?php foreach($inventory as $item): ?>
                        <tr>
                            <td><span class="badge badge-primary"><?= esc($item['item_id']) ?></span></td>
                            <td><?= esc($item['item_name']) ?></td>
                            <td><span class="badge badge-outline-secondary"><?= esc(ucfirst($item['category'])) ?></span></td>
                            <td>
                                <span class="badge badge-outline-info">
                                    <?= esc($item['quantity']) ?> <?= esc($item['unit'] ?: 'units') ?>
                                </span>
                            </td>
                            <td><?= esc($item['unit'] ?: 'N/A') ?></td>
                            <td><?= esc($item['expiry_date'] ?: 'No expiry') ?></td>
                            <td><?= esc($item['updated_at']) ?></td>
                            <td>
                                <a href="<?= base_url('/inventory/edit/' . $item['item_id']) ?>" class="btn btn-sm btn-ghost-secondary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= base_url('/inventory/delete/' . $item['item_id']) ?>" class="btn btn-sm btn-ghost-danger" onclick="return confirm('Delete item #<?= esc($item['item_id']) ?>')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No items in inventory</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
