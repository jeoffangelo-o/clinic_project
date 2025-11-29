<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>

<br><br>
<div class="page-header d-print-none">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">Edit Inventory Item</h2>
        </div>
        <div class="col-auto">
            <a href="/inventory" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>
<br>
<?php if(!empty($item)): ?>

<div class="card">
    <div class="card-body">
        <form action="/inventory/update/<?= $item['item_id'] ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="item_name" class="form-control" value="<?= $item['item_name'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="medicine" <?= $item['category'] == 'medicine' ? 'selected' : '' ?>>Medicine</option>
                            <option value="supply" <?= $item['category'] == 'supply' ? 'selected' : '' ?>>Supply</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" value="<?= $item['quantity'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" value="<?= $item['unit'] ?: '' ?>" placeholder="e.g., pcs, bottles, boxes">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control" value="<?= $item['expiry_date'] ?: '' ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"><?= $item['description'] ?: '' ?></textarea>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Update Item
                        </button>
                        <a href="/inventory" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
    <div class="alert alert-warning">
        <i class="fa-solid fa-circle-exclamation"></i> Item not found
    </div>
    <a href="/inventory" class="btn btn-secondary">Back to Inventory</a>
<?php endif; ?>

<?= $this->endSection() ?>
