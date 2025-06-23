<?php if(in_groups('admin')):?>
<?= $this->extend('layout/page_layout_admin') ?>
<?php else:?>
<?= $this->extend('layout/page_layout') ?>
<?php endif?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/cssproduct.css') ?>" />
<body>
    <div class="wrapper">
        <?php if(in_groups('admin')):?>
            <h1>Daftar Menu</h1>
            <br>
        <?php endif; ?>    
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success" style="
                background-color: #d4edda;
                color: #155724;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #c3e6cb;
                text-align: center;
                margin: 0px auto 10px auto;
            ">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" style="
                background-color: #f8d7da;
                color: #721c24;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #f5c6cb;
                text-align: center;
                margin: 0px auto 10px auto;
            ">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <div class="content">
            <?php if(in_groups('admin')):?>
                <a class="tombol tambah-menu" href=<?= site_url('/product/tambah_menu') ?>>+ Tambah Menu</a>
            <?php endif?>
            
            <div class="coloumn catalog">
                <?php if (empty($product)): ?>
                    <p style="text-align: center; margin-top: 50px; font-size: 1.2em; color: #666;">Tidak ada menu yang tersedia saat ini.</p>
                <?php else: ?>
                    <?php foreach ($product as $key => $post) : ?>
                        <div class="product-catalog">
                            <div class="img-wrapper">
                                <img src="/<?= $post['photo'] ?>" />
                            </div>
                            <p class="product-name"><?= $post['product_name'] ?></p>
                            <p class="price">Rp. <?= number_format($post['price'], 0, ',', '.') ?></p>
                            <p class="stock" style="text-align: end;">Stok: <?= esc($post['stock']) ?></p> <?php if(in_groups('user')):?>
                            <?php if ($post['stock'] > 0): ?>
                                <a class="addtocart" href=<?= site_url('product/tambah_ke_keranjang/' . $post['product_id']) ?>>
                                + Add to cart
                                </a>
                            <?php else: ?>
                                <p class="addtocart out-of-stock">Stok Habis</p>
                            <?php endif; ?>
                        <?php endif?>
                        <?php if(in_groups('admin')):?>
                            <?php if(in_groups('admin')):?>
                                <div class="bawah">
                                    <a class="tombol ud edit" href=<?= site_url('/product/edit_menu/' . $post['product_id']) ?>>✎</a>
                                    <a class="tombol ud hapus" href="<?= site_url('/product/delete_menu/' . $post['product_id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini: <?= esc($post['product_name']) ?>?');">🗑</a>
                                </div>
                            <?php endif?>
                        <?php endif?>
                        </div>
                    <?php endforeach ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<?= $this->endSection() ?>