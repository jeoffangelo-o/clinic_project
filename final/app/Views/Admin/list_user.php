<?= $this->extend('layouts/sidebar') ?>

<?= $this->section('mainContent') ?>

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
                <a href="<?= base_url('/add_user') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add User
                </a>
            </div>
        </div>
    </div>

    <?php if(session()->getFlashData('message')): ?>
        <div data-flash-message="info" style="display: none;"><?= session()->getFlashData('message') ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="table-responsive">
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