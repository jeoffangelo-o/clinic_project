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

<div class="card">
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
