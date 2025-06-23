<?= $this->extend('layout/page_layout') ?>
<?= $this->section('content') ?>
<style>
    /* General Styling */
    body {
        
        background-color: #f4f4f4;
        color: #333;
    }

    .content {
        padding: 20px;
        max-width: 1000px;
        margin: 20px auto;
        display: flex; /* Using flexbox for layout */
        flex-direction: column; /* Stack columns vertically on small screens */
        gap: 20px; /* Space between columns */
    }

    .cart-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 25px;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #4CAF50;
        font-size: 2em;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    /* Table Styling */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .cart-table th, .cart-table td {
        border: 1px solid #e9ecef;
        padding: 12px 15px;
        text-align: center;
    }

    .cart-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #555;
        text-transform: uppercase;
        font-size: 0.9em;
    }

    .cart-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .cart-table tr:hover {
        background-color: #e9e9e9;
    }

    .cart-table img {
        width: 80px;
        height: auto;
        border-radius: 4px;
        object-fit: cover;
    }

    .product-name {
        font-weight: bold;
        color: #444;
    }

    .price {
        color: #e44d26; /* Orange-red for prices */
        font-weight: bold;
    }

    .qty-control {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        text-decoration: none;
    }

    .qty-control .qty-input {
        width: 50px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 8px 0;
        font-size: 1em;
    }

    .qty-control .btn-qty {
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.9em;
        color: white;
        transition: background-color 0.2s ease;
        text-decoration: none;
    }

    .qty-control .btn-danger {
        background-color: #dc3545;
    }

    .qty-control .btn-danger:hover {
        background-color: #c82333;
    }

    .qty-control .btn-primary {
        background-color: #007bff;
    }

    .qty-control .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Total and Checkout Button */
    .cart-summary-row {
        background-color: #f8f8f8;
        font-weight: bold;
        font-size: 1.1em;
    }

    .cart-summary-row td:first-child {
        text-align: right;
        padding-right: 20px;
    }

    .cart-summary-row td:last-child {
        color: #4CAF50; /* Consistent green for total */
    }

    .checkout-button-container {
        text-align: right;
        
    }

    .checkout-button {
        padding: 12px 25px;
        background-color: #28a745; /* Green for checkout */
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 1.1em;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .checkout-button:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .content {
            padding: 10px;
        }

        .cart-table th, .cart-table td {
            padding: 8px 10px;
            font-size: 0.85em;
        }

        .cart-table img {
            width: 60px;
        }

        .qty-control .qty-input {
            width: 40px;
            padding: 5px 0;
        }

        .qty-control .btn-qty {
            padding: 6px 10px;
        }

        .checkout-button {
            padding: 10px 20px;
            font-size: 1em;
        }
        
    }
</style>

<body>
    <div class="content">
        <div class="cart-container">
            <h2>Keranjang Belanja Anda</h2>
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
            <?php if (empty($carts)): ?>
                <p style="text-align: center; padding: 20px; font-size: 1.1em; color: #666;">Keranjang Anda kosong. Yuk, mulai belanja!</p>
            <?php else: ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($carts as $key => $post) :
                            $post['price'] = str_replace('.', '', $post['price']); // Pastikan harga dalam format numerik
                            $total += $post['price'] * $post['qty'];
                        ?>
                            <tr class="align-middle">
                                <td><img src="/<?= esc($post['photo']) ?>" alt="<?= esc($post['product_name']) ?>" /></td>
                                <td class="product-name"><?= esc($post['product_name']) ?></td>
                                <td class="price">Rp. <?= number_format($post['price'], 0, ',', '.') ?></td>
                                <td>
                                    <div class="qty-control">
                                        <?php if ($post['qty'] > 1) : ?>
                                            <a href="<?= site_url('cart/kurang_qty/' . esc($post['product_id'])) ?>" class="btn-qty btn-danger">
                                                <i>-</i>
                                            </a>
                                        <?php else : ?>
                                            <a href="<?= site_url('cart/hapus/' . esc($post['product_id'])) ?>" class="btn-qty btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')">
                                                <i>-</i>
                                            </a>
                                        <?php endif ?>
                                        <input type="text" value="<?= esc($post['qty']) ?>" class="qty-input" disabled />
                                        <a href="<?= site_url('cart/tambah_qty/' . esc($post['product_id'])) ?>" class="btn-qty btn-primary">
                                            <i >+</i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr class="cart-summary-row">
                            <td colspan="2">Total Keseluruhan</td>
                            <td colspan="2">Rp. <?= number_format($total, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($carts)): ?>
            <div class="checkout-button-container">
                <a href="<?= site_url('cart/checkout') ?>" class="checkout-button">Lanjutkan ke Checkout</a>
            </div>
        <?php endif; ?>
    </div>
</body>
<?= $this->endSection() ?>