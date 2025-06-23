<?= $this->extend('layout/page_layout') ?>
<?= $this->section('content') ?>

<style>
    /* Styling Umum */
    body {
        
        background-color: #f4f4f4;
        color: #333;
    }

    .content-wrapper {
        padding: 20px;
        max-width: 900px;
        margin: 0px auto;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #4CAF50; /* Warna hijau yang konsisten */
        font-size: 2.2em;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    /* Styling untuk setiap grup pesanan */
    .order-group {
        border: 1px solid #e0e0e0;
        margin-bottom: 25px;
        border-radius: 8px;
        overflow: hidden;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    }

    .order-header {
        background-color: #f0f0f0;
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap; /* Agar responsif pada layar kecil */
    }

    .order-header h5 {
        margin: 0;
        font-size: 1.2em;
        color: #333;
    }

    .order-status-badges {
        display: flex;
        gap: 10px;
        margin-top: 5px; /* Untuk responsif */
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: bold;
        color: white;
        text-transform: capitalize; /* Huruf pertama kapital */
    }

    /* Warna badge status */
    .status-date { background-color: #007bff; } /* Biru */
    .status-verification-menunggu-verifikasi { background-color: #ffc107; color: #333; } /* Kuning */
    .status-verification-terverifikasi { background-color: #28a745; } /* Hijau */
    .status-verification-ditolak { background-color: #dc3545; } /* Merah */
    .status-item-menunggu-konfirmasi { background-color: #17a2b8; } /* Biru muda */
    .status-item-diproses { background-color: #6f42c1; } /* Ungu */
    .status-item-dalam-perjalanan { background-color: #fd7e14; } /* Oranye */
    .status-item-selesai { background-color: #20c997; } /* Teal */
    .status-item-ditolak { background-color: #dc3545; } /* Merah */


    .order-body {
        padding: 20px;
    }

    .order-info p {
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .order-info strong {
        color: #444;
    }

    .order-info a {
        color: #007bff;
        text-decoration: none;
    }
    .order-info a:hover {
        text-decoration: underline;
    }
    .rejection-reason {
        color: #dc3545; /* Merah untuk alasan penolakan */
        font-style: italic;
        margin-top: 5px;
    }

    h6 {
        margin-top: 20px;
        margin-bottom: 10px;
        color: #555;
        font-size: 1.1em;
    }

    .order-item-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 0.95em;
    }

    .order-item-table th, .order-item-table td {
        border: 1px solid #e9ecef;
        padding: 10px;
        text-align: left;
    }

    .order-item-table th {
        background-color: #e2e6ea;
        font-weight: bold;
        color: #444;
    }

    .order-item-table tr:nth-child(even) {
        background-color: #f8f9fa;
    }
    .order-item-table tr:hover {
        background-color: #f0f0f0;
    }

    .order-summary {
        margin-top: 20px;
        font-weight: bold;
        text-align: right;
        font-size: 1.1em;
        color: #4CAF50;
        padding-top: 10px;
        border-top: 1px dashed #e0e0e0;
    }

    /* Jika tidak ada pesanan */
    .no-orders-message {
        text-align: center;
        margin-top: 50px;
        font-size: 1.2em;
        color: #666;
        padding: 30px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }
</style>

<body>
    <div class="content-wrapper">
        <h1>Daftar Pesananku</h1>
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

        <?php if (empty($orders)): ?>
            <p class="no-orders-message">Anda belum memiliki pesanan.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-group">
                    <div class="order-header">
                        <h5>Pesanan ID: <?= esc($order['order_id']) ?></h5>
                        <div class="order-status-badges">
                            <span class="status-badge status-date">Tanggal: <?= date('d F Y', strtotime($order['order_date'])) ?></span>
                            <span class="status-badge status-verification-<?= str_replace(' ', '-', esc($order['verification_status'])) ?>">
                                Verifikasi Pembayaran: <?= esc($order['verification_status']) ?>
                            </span>
                        </div>
                    </div>
                    <div class="order-body">
                        <div class="order-info">
                            <p><strong>Nama:</strong> <?= esc($order['name']) ?></p>
                            <p><strong>Nomor Telepon:</strong> <?= esc($order['phone_number']) ?></p>
                            <p><strong>Alamat:</strong> <?= esc($order['address']) ?></p>
                            <p><strong>Bukti Transfer:</strong>
                                <?php if ($order['payment_proof']): ?>
                                    <a href="<?= base_url('uploads/payment_proof/' . esc($order['payment_proof'])) ?>" target="_blank">Lihat Bukti</a>
                                <?php else: ?>
                                    Tidak ada
                                <?php endif; ?>
                            </p>
                            <?php if ($order['rejection_reason']): ?>
                                <p class="rejection-reason"><strong>Catatan Admin (Verifikasi):</strong> <?= esc($order['rejection_reason']) ?></p>
                            <?php endif; ?>
                        </div>

                        <h6>Detail Produk:</h6>
                        <table class="order-item-table">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Status Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item): ?>
                                    <tr>
                                        <td><?= esc($item['product_name']) ?></td>
                                        <td>Rp. <?= number_format($item['price'], 0, ',', '.') ?></td>
                                        <td><?= esc($item['qty']) ?></td>
                                        <td>
                                            <span class="status-badge status-item-<?= str_replace(' ', '-', esc($item['status'])) ?>">
                                                <?= esc($item['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="order-summary">
                            Total Harga Pesanan: Rp. <?= number_format($order['total_price'], 0, ',', '.') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
<?= $this->endSection() ?>