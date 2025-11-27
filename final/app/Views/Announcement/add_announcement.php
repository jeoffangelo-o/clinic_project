<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>

<div class="page-header d-print-none">
    <h2 class="page-title">Create Announcement</h2>
</div>

<?php if(session()->getFlashData('message')): ?>
    <div data-flash-message="success" style="display: none;"><?= session()->getFlashData('message') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="/announcement/store" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Image URL</label>
                        <input type="url" name="url" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Posted Until <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="posted_until" class="form-control" required>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Create Announcement
                        </button>
                        <a href="/announcement" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>