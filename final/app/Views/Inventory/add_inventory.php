<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>

<br><br>
<div class="page-header d-print-none">
    <h2 class="page-title">
        <i class="fas fa-box"></i> Add Inventory Item
    </h2>
    <p class="text-muted">Add a new item to inventory</p>
</div>
<br>
<div class="card">
    <div class="card-body">
        <form action="/inventory/store" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Item Name <span class="text-danger">*</span></label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <option value="medicine">Medicine</option>
                            <option value="supply">Supply</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control" value="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" placeholder="e.g., pcs, bottles, boxes">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i> Add Item
                        </button>
                        <a href="/inventory" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
