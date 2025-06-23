<?= $this->extend('layout/page_layout')?>
<?= $this->section('content')?>
<style>
    /* General Styling */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    .content {
        padding: 20px;
        max-width: 1200px;
        margin: 20px auto;
        display: flex;
        gap: 30px; /* Space between checkout form and order summary */
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
        justify-content: center;
    }

    /* Checkout Form Section */
    .checkout-form-section {
        flex: 2; /* Takes more space */
        min-width: 450px; /* Minimum width for the form */
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }

    .checkout-form-section h3 {
        text-align: center;
        margin-bottom: 25px;
        color: #4CAF50;
        font-size: 1.8em;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }

    .form-group input[type="text"],
    .form-group textarea,
    .form-group input[type="file"] {
        width: calc(100% - 22px); /* Adjust for padding and border */
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1em;
    }

    .form-group textarea {
        resize: vertical; /* Allow vertical resizing */
        min-height: 80px;
    }

    .form-group input[type="file"] {
        padding: 5px; /* Adjust padding for file input */
        border: none; /* File input might look better without default border */
    }

    .btn-checkout {
        display: block;
        width: 100%;
        padding: 12px;
        background-color: #28a745; /* Green for checkout */
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1.1em;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.2s ease;
        margin-top: 20px;
    }

    .btn-checkout:hover {
        background-color: #218838;
        transform: translateY(-2px);
    }

    /* Order Summary Section */
    .order-summary-section {
        flex: 1; /* Takes remaining space */
        min-width: 300px; /* Minimum width for summary */
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 25px;
        max-height: fit-content; /* Adjust height to content */
    }

    .order-summary-section h4 {
        text-align: center;
        margin-bottom: 20px;
        color: #555;
        font-size: 1.5em;
    }

    .order-summary-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .order-summary-table th, .order-summary-table td {
        border: 1px solid #e9ecef;
        padding: 10px;
        text-align: center;
    }

    .order-summary-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #555;
        font-size: 0.9em;
    }

    .order-summary-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .order-summary-table img {
        width: 60px;
        height: auto;
        border-radius: 4px;
        object-fit: cover;
    }

    .order-total-row {
        background-color: #f8f8f8;
        font-weight: bold;
        font-size: 1.1em;
    }

    .order-total-row td:first-child {
        text-align: right;
        padding-right: 20px;
    }

    .order-total-row td:last-child {
        color: #4CAF50;
    }

    .payment-info {
        background-color: #e9f7ef; /* Warna latar belakang hijau muda */
        border: 1px solid #c8e6c9; /* Border hijau */
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
        text-align: left;
    }

    .payment-info p {
        margin: 5px 0;
        font-size: 0.95em;
        color: #388e3c; /* Warna teks hijau gelap */
    }

    .payment-info strong {
        color: #2e7d32; /* Warna teks hijau lebih gelap */
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .content {
            flex-direction: column;
            gap: 20px;
            padding: 10px;
        }

        .checkout-form-section, .order-summary-section {
            min-width: unset; /* Remove min-width on small screens */
            width: 100%;
        }
    }
</style>

<body>
    <div class="content">
        <div class="checkout-form-section">
            <h3>Formulir Checkout</h3>
            <form action="<?= site_url('cart/checkout_process') ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" name="name" id="name" required />
                </div>
                <div class="form-group">
                    <label for="phone_number">Nomor Telepon</label>
                    <input type="text" name="phone_number" id="phone_number" required />
                </div>
                <div class="form-group">
                    <label for="address">Alamat Pengiriman</label>
                    <textarea name="address" id="address" required></textarea>
                </div>
                <div class="payment-info">
                    <p>Silakan lakukan transfer ke salah satu rekening berikut:</p>
                    <p><strong>Bank BCA:</strong> 1234567890 (a.n. Rumah Makan Husnul Khatimah)</p>
                    <p><strong>Bank Mandiri:</strong> 0987654321 (a.n. Rumah Makan Husnul Khatimah)</p>
                    <p>Setelah transfer, unggah bukti transfer Anda pada field di bawah ini.</p>
                </div>
                <div class="form-group">
                    <label for="payment_proof">Unggah Bukti Transfer (Gambar)</label>
                    <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required>
                </div>
                <button type="submit" class="btn-checkout">Selesaikan Pesanan</button>
            </form>
        </div>

        <div class="order-summary-section">
            <h4>Ringkasan Pesanan Anda</h4>
            <?php if (empty($carts)): ?>
                <p style="text-align: center; padding: 20px; font-size: 1.1em; color: #666;">Keranjang Anda kosong.</p>
            <?php else: ?>
                <table class="order-summary-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($carts as $key => $post) :
                            $post['price'] = str_replace('.', '', $post['price']);
                            $total += $post['price'] * $post['qty'];
                        ?>
                            <tr>
                                <td><img src="/<?= esc($post['photo']) ?>" alt="<?= esc($post['product_name']) ?>" /></td>
                                <td><?= esc($post['product_name']) ?></td>
                                <td>Rp. <?= number_format($post['price'], 0, ',', '.') ?></td>
                                <td><?= esc($post['qty']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                    <tfoot>
                        <tr class="order-total-row">
                            <td colspan="2">Total Pembayaran</td>
                            <td colspan="2">Rp. <?= number_format($total, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
<?= $this->endSection()?>