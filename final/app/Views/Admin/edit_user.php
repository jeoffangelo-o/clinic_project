<?= $this->extend('layouts/sidebar') ?>
<?= $this->section('mainContent') ?>

<br><br>
<div class="page-header d-print-none">
    <h2 class="page-title">Edit User: <span class="text-muted"><?= esc($user['username']) ?></span></h2>
</div>
<br>
<div class="card">
    <div class="card-body">
        <form action="<?= base_url('/update_user/'.$user['user_id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Old Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                        <small class="text-muted">Only required if changing password</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="newpassword" class="form-control" placeholder="Leave blank to keep current password">
                        <small class="text-muted">Leave blank to keep current password</small>
                    </div>

                    <?php if(session()->get('role')==='admin'): ?>
                        <div class="mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
                                <option value="nurse" <?= $user['role'] === 'nurse' ? 'selected' : '' ?>>Nurse</option>
                                <option value="staff" <?= $user['role'] === 'staff' ? 'selected' : '' ?>>Staff</option>
                                <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                            </select>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="role" value="<?= esc($user['role']) ?>">
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <div class="form-control-plaintext">
                                <span class="badge bg-primary"><?= ucfirst(esc($user['role'])) ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-check"></i> Update User
                        </button>
                        <a href="/list_user" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>