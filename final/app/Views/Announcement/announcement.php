<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>
<br><br>
<div class="page-header d-print-none">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h2 class="page-title">
                <i class="fas fa-bullhorn"></i> Announcements
            </h2>
            <p class="text-muted">View system announcements and updates</p>
        </div>
        <div class="col-auto">
            <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>
                <a href="<?= base_url('/announcement/add') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Announcement
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if(!empty($announce)): ?>
    <div class="row">
        <?php foreach($announce as $a): ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title"><?= esc($a['title']) ?></h3>
                                <p class="text-muted text-sm"><?= esc($a['posted_at']) ?></p>
                            </div>
                            <?php if(!empty($a['url'])): ?>
                                <div class="col-auto">
                                    <img src="<?= esc($a['url'], 'attr') ?>" alt="announcement" style="height: 60px; width: 60px; object-fit: cover; border-radius: 4px;">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <p><?= esc($a['content']) ?></p>
                    </div>
                    <?php if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'): ?>
                        <div class="card-footer text-end">
                            <a href="<?= base_url('/announcement/edit/' . $a['announcement_id']) ?>" class="btn btn-secondary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="<?= base_url('/announcement/delete/' . $a['announcement_id']) ?>" class="btn btn-danger" onclick="return confirm('Delete this announcement?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty">
        <div class="empty-header"><i class="fas fa-inbox"></i></div>
        <p class="empty-title">No Announcements Yet</p>
        <p class="empty-subtitle">Check back later for updates</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>