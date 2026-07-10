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
<?php if (empty($items)) : ?>
    <div class="text-center">
        <p class="fs-5">Keranjang belanja masih kosong</p>
        <a href="<?= base_url('/') ?>" class="btn btn-primary">Belanja Sekarang</a>
    </div>
<?php else : ?>
    <?php if ($discount) : ?>
        <div class="alert alert-success">
            Diskon Hari Ini: Rp <?= number_format($discount_nominal, 0, ',', '.') ?> per produk
        </div>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Nama</th>
                    <th scope="col">Harga Satuan</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Sub Total</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $index => $item) : ?>
                    <tr>
                        <td><?= $item['productTitle'] ?></td>
                        <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                        <td><a class="btn btn-danger btn-sm" href="<?= base_url() ?>keranjang/remove/<?= $item['productId'] ?>">Hapus</a></td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <td colspan="2"></td>
                    <td>Subtotal</td>
                    <td><?= number_to_currency($total, 'IDR') ?></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Total</td>
                    <td><?= number_to_currency($total, 'IDR') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <a class="btn btn-success" href="<?= base_url() ?>checkout">Selesai Belanja</a>
<?php endif; ?>
<?= $this->endSection() ?>
