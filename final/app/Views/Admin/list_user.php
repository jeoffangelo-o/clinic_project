<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>
<br><br>
<?php if(session()->get('role') === 'admin'): ?>
    <div class="page-header d-print-none">
        <div class="row align-items-center mb-3">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-users"></i> Manage Users
                </h2>
                <p class="text-muted">View and manage system users and roles</p>
            </div>
            <div class="col-auto">
                
            </div>
        </div>
    </div>
<br>
    
    <div class="card">
        <div class="card-header">
           
            <form method="get" action="<?= base_url('/list_user') ?>" class="row g-3">
                <div class="col-12">
                    <div class="d-flex gap-3">
                        <div class="flex-grow-1">
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by username, email, role..." value="<?= esc(request()->getGet('search') ?? '') ?>">
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <?php if(request()->getGet('search')): ?>
                            <a href="<?= base_url('/list_user') ?>" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-12">
                    <div class="d-flex gap-2 align-items-center">
                        <span class="text-dark fw-bold">Sort by ID:</span>
                        <div class="btn-group" role="group">
                            <a href="<?= base_url('/list_user?sort=asc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'asc') ? 'btn-info' : 'btn-outline-info' ?>">
                                <i class="fas fa-arrow-up"></i> Ascending
                            </a>
                            <a href="<?= base_url('/list_user?sort=desc' . (request()->getGet('search') ? '&search=' . esc(request()->getGet('search')) : '')) ?>" class="btn <?= (request()->getGet('sort') === 'desc' || empty(request()->getGet('sort'))) ? 'btn-info' : 'btn-outline-info' ?>">
                                <i class="fas fa-arrow-down"></i> Descending
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<br>
    
    <div class="card">
        <div class="card-header"></div>
            <table class="table table-vcenter card-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Date Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($user)): ?>
                        <?php foreach($user as $u): ?>
                            <tr>
                                <td><span class="badge badge-primary"><?= esc($u['user_id']) ?></span></td>
                                <td><?= esc($u['username']) ?></td>
                                <td><?= esc($u['email']) ?></td>
                                <td><span class="badge badge-outline-secondary"><?= esc(ucfirst($u['role'])) ?></span></td>
                                <td><?= esc($u['created_at']) ?></td>
                                <td>
                                    <a href="<?= base_url('/edit_user/' . $u['user_id']) ?>" class="btn btn-sm btn-ghost-secondary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="<?= base_url('/delete_user/' . $u['user_id']) ?>" class="btn btn-sm btn-ghost-danger" onclick="return confirm('Delete user <?= esc($u['username']) ?> (#<?= $u['user_id'] ?>)?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>
    <div class="empty">
        <div class="empty-header"><i class="fas fa-lock"></i></div>
        <p class="empty-title">Access Denied</p>
        <p class="empty-subtitle">Only administrators can view this page</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>