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
<br>
<div class="card">
    <div class="card-header">
       
        <form method="get" action="<?= base_url('/announcement') ?>" class="row g-3">
            <div class="col-12">
                <div class="d-flex gap-3">
                    <div class="flex-grow-1">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by title, content..." value="<?= esc(request()->getGet('search') ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <?php if(request()->getGet('search')): ?>
                        <a href="<?= base_url('/announcement') ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex gap-2 align-items-center">
                    <span class="text-dark fw-bold">Sort by ID:</span>
                    <div class="btn-group" role="group">
                        <a href="<?= base_url('/announcement?sort=asc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'asc') ? 'btn-info' : 'btn-outline-info' ?>">
                            <i class="fas fa-arrow-up"></i> Ascending
                        </a>
                        <a href="<?= base_url('/announcement?sort=desc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'desc' || empty(request()->getGet('sort'))) ? 'btn-info' : 'btn-outline-info' ?>">
                            <i class="fas fa-arrow-down"></i> Descending
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<br>
<?php if(!empty($announce)): ?>
    <div class="row">
        <?php foreach($announce as $a): ?>
            <div class="col-md-6">
                <div class="card mb-3 h-100">
                    <?php if(!empty($a['url']) && filter_var($a['url'], FILTER_VALIDATE_URL)): ?>
                        <img src="<?= esc($a['url'], 'attr') ?>" alt="announcement" class="card-img-top" style="height: 250px; object-fit: cover; border-radius: 8px 8px 0 0;">
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                            <div class="text-center">
                                <i class="fas fa-image fa-3x text-secondary"></i>
                                <p class="text-secondary mt-2">No image available</p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h3 class="card-title"><?= esc($a['title']) ?></h3>
                        <p class="text-secondary mb-3"><small><i class="fas fa-calendar"></i> <?= esc($a['posted_at']) ?></small></p>
                        <p class="card-text text-dark"><?= esc($a['content']) ?></p>
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