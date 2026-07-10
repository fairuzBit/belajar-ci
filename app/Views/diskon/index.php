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
<button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
    Tambah Diskon
</button>
<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Tanggal</th>
            <th scope="col">Nominal</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($discounts as $index => $item) : ?>
            <tr>
                <th scope="row"><?= $index + 1 ?></th>
                <td><?= $item['tanggal'] ?></td>
                <td>Rp <?= number_format($item['nominal'], 0, ',', '.') ?></td>
                <td>
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal-<?= $item['id'] ?>">
                        Ubah
                    </button>
                    <a href="<?= base_url('diskon/delete/' . $item['id']) ?>" class="btn btn-danger" onclick="return confirm('Yakin hapus diskon ini ?')">
                        Hapus
                    </a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Diskon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open(base_url('diskon/create')) ?>
            <?= csrf_field() ?>
            <div class="modal-body">
                <div class="mb-3">
                    <?= form_label('Tanggal', 'tanggal') ?>
                    <?= form_input([
                        'name' => 'tanggal',
                        'id' => 'tanggal',
                        'type' => 'date',
                        'class' => 'form-control',
                        'required' => true
                    ]) ?>
                </div>
                <div class="mb-3">
                    <?= form_label('Nominal Diskon', 'nominal') ?>
                    <?= form_input([
                        'name' => 'nominal',
                        'id' => 'nominal',
                        'type' => 'number',
                        'class' => 'form-control',
                        'placeholder' => 'Nominal Diskon',
                        'required' => true
                    ]) ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?= form_submit('submit', 'Simpan', ['class' => 'btn btn-primary']) ?>
            </div>
            <?= form_close() ?>
        </div>
    </div>
</div>

<?php foreach ($discounts as $item) : ?>
    <div class="modal fade" id="editModal-<?= $item['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Diskon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?= form_open(base_url('diskon/edit/' . $item['id'])) ?>
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <?= form_label('Tanggal', 'tanggal') ?>
                        <?= form_input([
                            'name' => 'tanggal',
                            'id' => 'tanggal',
                            'type' => 'date',
                            'class' => 'form-control',
                            'value' => $item['tanggal'],
                            'readonly' => true
                        ]) ?>
                    </div>
                    <div class="mb-3">
                        <?= form_label('Nominal Diskon', 'nominal') ?>
                        <?= form_input([
                            'name' => 'nominal',
                            'id' => 'nominal',
                            'type' => 'number',
                            'class' => 'form-control',
                            'value' => $item['nominal'],
                            'required' => true
                        ]) ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <?= form_submit('submit', 'Simpan', ['class' => 'btn btn-primary']) ?>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
<?php endforeach ?>

<?= $this->endSection() ?>
