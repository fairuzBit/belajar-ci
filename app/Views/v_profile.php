<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
if (session()->getFlashData('success')) {
?>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
if (session()->getFlashData('error')) {
?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Profil Saya</h5>
                <?= form_open('profile/update') ?>
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" value="<?= $user['username'] ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <?= form_input([
                        'name' => 'email',
                        'id' => 'email',
                        'class' => 'form-control',
                        'value' => $user['email'],
                        'required' => true
                    ]) ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru (kosongkan jika tidak ingin mengganti)</label>
                    <?= form_password([
                        'name' => 'password',
                        'id' => 'password',
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <?= form_submit('submit', 'Simpan', ['class' => 'btn btn-primary']) ?>
                <?= form_close() ?>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informasi Akun</h5>
                <table class="table table-borderless">
                    <tr>
                        <td>Username</td>
                        <td>: <?= $user['username'] ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>: <?= $user['email'] ?></td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td>: <?= ucfirst($user['role']) ?></td>
                    </tr>
                    <tr>
                        <td>Bergabung</td>
                        <td>: <?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
