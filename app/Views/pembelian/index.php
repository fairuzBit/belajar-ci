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
<table class="table datatable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Username</th>
            <th scope="col">Alamat</th>
            <th scope="col">Ongkir</th>
            <th scope="col">Total Harga</th>
            <th scope="col">Produk</th>
            <th scope="col">Status</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $index => $transaction) : ?>
            <tr>
                <th scope="row"><?= $index + 1 ?></th>
                <td><?= $transaction['username'] ?></td>
                <td><?= $transaction['alamat'] ?></td>
                <td>Rp <?= number_format($transaction['ongkir'], 0, ',', '.') ?></td>
                <td>Rp <?= number_format($transaction['total_harga'], 0, ',', '.') ?></td>
                <td>
                    <ul class="mb-0">
                        <?php if (isset($products[$transaction['id']])) : ?>
                            <?php foreach ($products[$transaction['id']] as $product) : ?>
                                <li><?= $product['nama'] ?> x <?= $product['jumlah'] ?> (Rp <?= number_format($product['subtotal_harga'], 0, ',', '.') ?>)</li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </td>
                <td>
                    <?php if ($transaction['status'] == 1) : ?>
                        <span class="badge bg-success">Selesai</span>
                    <?php else : ?>
                        <span class="badge bg-warning">Proses</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?= base_url('pembelian/status/' . $transaction['id']) ?>" class="btn btn-sm <?= $transaction['status'] == 1 ? 'btn-warning' : 'btn-success' ?>" onclick="return confirm('Ubah status ?')">
                        <?= $transaction['status'] == 1 ? 'Batalkan' : 'Selesaikan' ?>
                    </a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<?= $this->endSection() ?>
