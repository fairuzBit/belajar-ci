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
?>
<div class="row">
    <?php foreach ($products as $key => $item) : ?>         
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="<?= base_url() . "img/" . $item['foto'] ?>" alt="..." width="50%" class="mb-3">
                        <h5 class="card-title"><?= $item['nama'] ?></h5>
                        <?php if ($discount && $discount['nominal'] > 0) : ?>
                            <p class="card-text text-muted"><s>Rp <?= number_format($item['harga'], 0, ',', '.') ?></s></p>
                            <p class="card-text text-danger fw-bold">Rp <?= number_format($item['harga_diskon'], 0, ',', '.') ?></p>
                            <p class="text-success small">Diskon Rp <?= number_format($discount['nominal'], 0, ',', '.') ?></p>
                        <?php else : ?>
                            <p class="card-text">Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                        <?php endif; ?>
                        <p class="card-text">Stok: <?= $item['jumlah'] ?></p>
                        <?php if ($item['jumlah'] > 0) : ?>
                            <?= form_open('keranjang/add') ?>
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <?= form_submit('submit', 'Tambah ke Keranjang', ['class' => 'btn btn-primary']) ?>
                            <?= form_close() ?>
                        <?php else : ?>
                            <button class="btn btn-secondary" disabled>Stok Habis</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div> 
    <?php endforeach ?> 
</div>
<?= $this->endSection() ?>
