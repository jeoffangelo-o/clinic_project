<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>
<br><br>
<div class="page-header d-print-none">
    <h2 class="page-title">Edit Announcement</h2>
</div>
<br>
<div class="card">
    <div class="card-body">
        <form action="/announcement/update/<?= $announce['announcement_id'] ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Image URL</label>
                        <input type="url" name="url" class="form-control" value="<?= $announce['url'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="<?= $announce['title'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="5" required><?= $announce['content'] ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Posted Until <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="posted_until" class="form-control" value="<?= $announce['posted_until'] ?>" required>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Update Announcement
                        </button>
                        <a href="/announcement" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>