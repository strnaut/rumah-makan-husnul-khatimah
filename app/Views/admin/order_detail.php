<?= $this->extend('layout/page_layout_admin') ?>
<?= $this->section('content') ?>

<style>
    /* Styling Umum */
    .content-wrapper {
        padding: 20px;
        max-width: 900px;
        margin: 0px auto;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
        font-size: 2.2em;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    .order-info-section {
        padding: 20px;
    }

    .order-info-section p {
        margin-bottom: 10px;
        line-height: 1.6;
    }

    .order-info-section strong {
        color: #444;
    }

    .rejection-reason {
        color: #dc3545; /* Merah untuk alasan penolakan */
        font-style: italic;
        margin-top: 10px;
        padding: 10px;
        border: 1px dashed #dc3545;
        border-radius: 5px;
    }

    .payment-proof-section {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .payment-proof-section img {
        max-width: 300px;
        height: auto;
        border: 1px solid #ddd;
        padding: 5px;
        border-radius: 5px;
        display: block;
        margin-top: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .payment-proof-section p {
        font-weight: bold;
        color: #555;
    }

    .status-form-section {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .status-form-section h4 {
        margin-bottom: 15px;
        color: #555;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }

    .form-group select, .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1em;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .btn-submit {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        transition: background-color 0.2s ease;
    }
    .btn-submit:hover {
        background-color: #218838;
    }

    .btn-back {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #6c757d;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .btn-back:hover {
        background-color: #5a6268;
    }

    /* Order Items Table */
    .order-item-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
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

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: bold;
        color: white;
        text-transform: capitalize;
        display: inline-block;
        min-width: 90px;
        text-align: center;
    }

    /* Warna badge status verifikasi */
    .status-verification-menunggu-verifikasi { background-color: #ffc107; color: #333; } /* Kuning */
    .status-verification-terverifikasi { background-color: #28a745; } /* Hijau */
    .status-verification-ditolak { background-color: #dc3545; } /* Merah */
    /* Warna badge status item */
    .status-item-menunggu-konfirmasi { background-color: #17a2b8; } /* Biru muda */
    .status-item-diproses { background-color: #6f42c1; } /* Ungu */
    .status-item-dalam-perjalanan { background-color: #fd7e14; } /* Oranye */
    .status-item-selesai { background-color: #20c997; } /* Teal */
    .status-item-ditolak { background-color: #dc3545; } /* Merah */

</style>

<body>
    <div class="content-wrapper">
        <h1>Detail Pesanan #<?= esc($order['order_id']) ?></h1>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="order-info-section">
            <p><strong>Nama Pelanggan:</strong> <?= esc($order['customer_name']) ?></p>
            <p><strong>Nomor Telepon:</strong> <?= esc($order['phone_number']) ?></p>
            <p><strong>Alamat:</strong> <?= esc($order['address']) ?></p>
            <p><strong>Tanggal Pesan:</strong> <?= date('d F Y H:i', strtotime($order['order_date'])) ?></p>
            <p>
                <strong>Status Verifikasi Pembayaran:</strong>
                <span class="status-badge status-verification-<?= str_replace(' ', '-', esc($order['verification_status'])) ?>">
                    <?= esc($order['verification_status']) ?>
                </span>
            </p>
            <?php if ($order['rejection_reason']): ?>
                <p class="rejection-reason"><strong>Alasan Ditolak (Verifikasi):</strong> <?= esc($order['rejection_reason']) ?></p>
            <?php endif; ?>

            <div class="payment-proof-section">
                <p><strong>Bukti Transfer:</strong></p>
                <?php if ($order['payment_proof']): ?>
                    <a href="<?= base_url('uploads/payment_proof/' . esc($order['payment_proof'])) ?>" target="_blank">
                        <img src="<?= base_url('uploads/payment_proof/' . esc($order['payment_proof'])) ?>" alt="Bukti Transfer" />
                    </a>
                <?php else: ?>
                    <p>Tidak ada bukti transfer diunggah.</p>
                <?php endif; ?>
            </div>
        </div>

        <h4>Detail Produk dalam Pesanan ini:</h4>
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

        <div class="status-form-section">
            <h4>Ubah Status Pesanan dan Verifikasi</h4>

            <form action="<?= site_url('admin/verifyPayment/' . $order['order_id']) ?>" method="post" onsubmit="return confirm('Anda yakin ingin mengubah status verifikasi pembayaran?');">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="verification_status">Status Verifikasi Pembayaran:</label>
                    <select name="verification_status" id="verification_status_<?= esc($order['order_id']) ?>" onchange="toggleRejectionReason(this)">
                        <option value="menunggu verifikasi" <?= ($order['verification_status'] == 'menunggu verifikasi') ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                        <option value="terverifikasi" <?= ($order['verification_status'] == 'terverifikasi') ? 'selected' : '' ?>>Terverifikasi</option>
                        <option value="ditolak" <?= ($order['verification_status'] == 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>
                <div class="form-group rejection-reason-container" style="display: <?= ($order['verification_status'] == 'ditolak') ? 'block' : 'none' ?>;">
                    <label for="rejection_reason">Alasan Penolakan:</label>
                    <textarea name="rejection_reason" id="rejection_reason_<?= esc($order['order_id']) ?>" rows="3" placeholder="Masukkan alasan penolakan jika status ditolak"><?= esc($order['rejection_reason']) ?></textarea>
                </div>
                <button type="submit" class="btn-submit">Update Verifikasi</button>
            </form>
            <br>

            <form action="<?= site_url('admin/updateStatus/' . $order['order_id']) ?>" method="post" onsubmit="return confirm('Anda yakin ingin mengubah status pesanan?');">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label for="order_item_status">Status Pengiriman/Pesanan (untuk semua item):</label>
                    <select name="status" id="order_item_status">
                        <option value="menunggu konfirmasi" <?= (isset($order['items'][0]) && $order['items'][0]['status'] == 'menunggu konfirmasi') ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                        <option value="diproses" <?= (isset($order['items'][0]) && $order['items'][0]['status'] == 'diproses') ? 'selected' : '' ?>>Diproses</option>
                        <option value="dalam perjalanan" <?= (isset($order['items'][0]) && $order['items'][0]['status'] == 'dalam perjalanan') ? 'selected' : '' ?>>Dalam Perjalanan</option>
                        <option value="selesai" <?= (isset($order['items'][0]) && $order['items'][0]['status'] == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                        <option value="ditolak" <?= (isset($order['items'][0]) && $order['items'][0]['status'] == 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>
                 <button type="submit" class="btn-submit">Update Status Pesanan</button>
            </form>
        </div>

        <a href="<?= site_url('admin/orders_list') ?>" class="btn-back">Kembali ke Daftar Pesanan</a>
    </div>

    <script>
        function toggleRejectionReason(selectElement) {
            const container = selectElement.closest('form').querySelector('.rejection-reason-container');
            const textarea = container.querySelector('textarea[name="rejection_reason"]');
            if (selectElement.value === 'ditolak') {
                container.style.display = 'block';
                textarea.setAttribute('required', 'required');
            } else {
                container.style.display = 'none';
                textarea.removeAttribute('required');
                textarea.value = ''; // Clear the reason if not 'ditolak'
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize rejection reason textarea visibility on page load
            const verificationSelect = document.getElementById('verification_status_<?= esc($order['order_id']) ?>');
            if (verificationSelect) {
                toggleRejectionReason(verificationSelect);
            }
        });
    </script>
</body>
<?= $this->endSection() ?>