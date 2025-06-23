<?= $this->extend('layout/page_layout_admin') ?>
<?= $this->section('content') ?>
<style>
    /* Tambahkan gaya untuk tabel dan form agar lebih rapi */
    body {
        font-family: Arial, sans-serif;
    }
    
    .order-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 20px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .order-card-header {
        background-color: #f0f0f0;
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer; /* Menambahkan cursor pointer untuk menunjukkan bisa diklik */
    }

    .order-card-header h3 {
        margin: 0;
        font-size: 1.2em;
        color: #333;
    }

    .toggle-icon {
        font-size: 1.5em;
        transition: transform 0.3s ease;
    }

    .order-card.collapsed .toggle-icon {
        transform: rotate(-90deg); /* Rotate for collapsed state */
    }

    .order-card-body {
        padding: 15px;
        display: none; /* Default hidden for collapsed state */
    }

    .order-card.expanded .order-card-body {
        display: block; /* Show when expanded */
    }

    .order-info p {
        margin-bottom: 5px;
    }

    .order-items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .order-items-table th, .order-items-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .order-items-table th {
        background-color: #f8f8f8;
    }

    .admin-actions {
        display: flex;
        gap: 15px;
        margin-top: 15px;
        border-top: 1px solid #eee;
        padding-top: 15px;
        flex-wrap: wrap; /* Add flex-wrap for responsiveness */
    }

    .admin-actions .form-group {
        flex: 1;
        min-width: 250px; /* Ensure form groups don't shrink too much */
    }

    .admin-actions label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .admin-actions select, .admin-actions textarea, .admin-actions button {
        width: 100%;
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .admin-actions button {
        background-color: #007bff;
        color: white;
        cursor: pointer;
        border: none;
        margin-top: 10px;
    }

    .admin-actions button:hover {
        opacity: 0.9;
    }

    .rejection-reason-textarea {
        margin-top: 5px;
    }

    .proof-img {
        max-width: 150px;
        height: auto;
        display: block;
        margin-top: 10px;
        border: 1px solid #eee;
        padding: 5px;
    }

    /* Styles for status badges (existing) */
    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8em;
        font-weight: bold;
        color: white;
        text-transform: capitalize;
    }

    .status-verification-menunggu-verifikasi { background-color: #ffc107; color: #333; }
    .status-verification-terverifikasi { background-color: #28a745; }
    .status-verification-ditolak { background-color: #dc3545; }
    .status-item-menunggu-konfirmasi { background-color: #17a2b8; }
    /* .status-item-diproses { background-color: #6f42c1; }
    .status-item-dalam-perjalanan { background-color: #fd7e14; }
    .status-item-selesai { background-color: #20c997; } */
    .status-item-ditolak { background-color: #dc3545; }

    /* Filter styles */
    .filter-container {
        margin-bottom: 20px;
        padding: 15px;
        background-color: #e9ecef;
        border-radius: 8px;
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }

    .filter-container label {
        font-weight: bold;
        color: #555;
    }

    .filter-container select {
        padding: 8px;
        border-radius: 4px;
        border: 1px solid #ccc;
        min-width: 200px;
    }
</style>
<body>
    <h1>Daftar Pesanan</h1>
    <br>

    <?php if(session()->getFlashdata('success')): ?>
        <p style="color:green"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <p style="color:red"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <div class="filter-container">
        <label for="filterStatus">Status Verifikasi:</label>
        <select id="filterStatus" onchange="applyFilter(this.value)">
            <option value="">Semua Status</option>
            <option value="menunggu verifikasi" <?= (isset($_GET['status']) && $_GET['status'] == 'menunggu verifikasi') ? 'selected' : '' ?>>Menunggu Verifikasi</option>
            <option value="terverifikasi" <?= (isset($_GET['status']) && $_GET['status'] == 'terverifikasi') ? 'selected' : '' ?>>Terverifikasi</option>
            <option value="ditolak" <?= (isset($_GET['status']) && $_GET['status'] == 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
        </select>
    </div>

    <?php if (empty($orders)): ?>
        <p>Tidak ada pesanan untuk ditampilkan.</p>
    <?php else: ?>
        <?php foreach($orders as $order): ?>
            <div class="order-card collapsed" id="order-<?= esc($order['order_id']) ?>">
                <div class="order-card-header" onclick="toggleOrder(<?= esc($order['order_id']) ?>)">
                    <h3>Pesanan ID: <?= esc($order['order_id']) ?></h3>
                    <div>
                        <span class="status-badge status-verification-<?= str_replace(' ', '-', esc($order['verification_status'])) ?>">
                            <?= esc($order['verification_status']) ?>
                        </span>
                        <span class="toggle-icon">&#9660;</span> </div>
                </div>
                <div class="order-card-body">
                    <div class="order-info">
                        <p><strong>Tanggal:</strong> <?= date('d F Y H:i', strtotime($order['order_date'])) ?></p>
                        <p><strong>Nama Customer:</strong> <?= esc($order['customer_name']) ?></p>
                        <p><strong>Nomor Telepon:</strong> <?= esc($order['phone_number']) ?></p>
                        <p><strong>Alamat:</strong> <?= esc($order['address']) ?></p>
                        <p><strong>Total Harga:</strong> Rp. <?= number_format($order['total_price'], 0, ',', '.') ?></p>
                        <p><strong>Bukti Transfer:</strong>
                            <?php if ($order['payment_proof']): ?>
                                <a href="<?= base_url('uploads/payment_proof/' . esc($order['payment_proof'])) ?>" target="_blank">Lihat Bukti</a>
                                <img src="<?= base_url('uploads/payment_proof/' . esc($order['payment_proof'])) ?>" class="proof-img" alt="Bukti Transfer">
                            <?php else: ?>
                                Tidak ada
                            <?php endif; ?>
                        </p>
                        <?php if ($order['rejection_reason']): ?>
                            <p style="color: red;"><strong>Catatan Admin (Verifikasi):</strong> <?= esc($order['rejection_reason']) ?></p>
                        <?php endif; ?>
                    </div>

                    <h6>Detail Produk dalam Pesanan ini:</h6>
                    <table class="order-items-table">
                        <thead>
                            <tr>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Status Item</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($order['items'] as $item): ?>
                                <tr>
                                    <td><?= esc($item['product_name']) ?></td>
                                    <td><?= esc($item['qty']) ?></td>
                                    <td>Rp. <?= number_format($item['price'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="status-badge status-item-<?= str_replace(' ', '-', esc($item['status'])) ?>">
                                            <?= esc($item['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="admin-actions">
                        <div class="form-group">
                            <label for="status_<?= $order['order_id'] ?>">Update Status Pesanan:</label>
                            <form action="<?= base_url('admin/updateStatus/'.$order['order_id']) ?>" method="post">
                                <select name="status" id="status_<?= $order['order_id'] ?>" required class="status-dropdown">
                                    <option value="menunggu konfirmasi" class="status-option-menunggu-konfirmasi" <?= ($order['items'][0]['status'] ?? '') == 'menunggu konfirmasi' ? 'selected' : '' ?>>Menunggu Konfirmasi</option>
                                    <option value="diproses" class="status-option-diproses" <?= ($order['items'][0]['status'] ?? '') == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                    <option value="dalam perjalanan" class="status-option-dalam-perjalanan" <?= ($order['items'][0]['status'] ?? '') == 'dalam perjalanan' ? 'selected' : '' ?>>Dalam Perjalanan</option>
                                    <option value="selesai" class="status-option-selesai" <?= ($order['items'][0]['status'] ?? '') == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                    <option value="ditolak" class="status-option-ditolak" <?= ($order['items'][0]['status'] ?? '') == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                </select>
                                <button type="submit">Update Status</button>
                            </form>
                        </div>

                        <div class="form-group">
                            <label for="verify_<?= $order['order_id'] ?>">Verifikasi Pembayaran:</label>
                            <form action="<?= base_url('admin/verifyPayment/'.$order['order_id']) ?>" method="post">
                                <select name="verification_status" id="verify_<?= $order['order_id'] ?>" required onchange="toggleRejectionReason(this)">
                                    <option value="menunggu verifikasi" <?= $order['verification_status'] == 'menunggu verifikasi' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                                    <option value="terverifikasi" <?= $order['verification_status'] == 'terverifikasi' ? 'selected' : '' ?>>Terverifikasi</option>
                                    <option value="ditolak" <?= $order['verification_status'] == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                </select>
                                <textarea name="rejection_reason" class="rejection-reason-textarea" placeholder="Alasan penolakan jika tidak jelas" style="display: <?= $order['verification_status'] == 'ditolak' ? 'block' : 'none' ?>;"><?= esc($order['rejection_reason']) ?></textarea>
                                <button type="submit">Verifikasi</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <script>
        function toggleOrder(orderId) {
            const orderCard = document.getElementById('order-' + orderId);
            orderCard.classList.toggle('collapsed');
            orderCard.classList.toggle('expanded');
        }

        function toggleRejectionReason(selectElement) {
            const textarea = selectElement.nextElementSibling;
            if (selectElement.value === 'ditolak') {
                textarea.style.display = 'block';
                textarea.setAttribute('required', 'required');
            } else {
                textarea.style.display = 'none';
                textarea.removeAttribute('required');
            }
        }

        function applyFilter(status) {
            let url = '<?= base_url('admin/orders_list') ?>';
            if (status) {
                url += '?status=' + status;
            }
            window.location.href = url;
        }

        // Initialize textarea display on page load for verification_status form
        document.addEventListener('DOMContentLoaded', function() {
            const verificationSelects = document.querySelectorAll('select[name="verification_status"]');
            verificationSelects.forEach(select => {
                toggleRejectionReason(select);
            });
        });
    </script>
</body>
<?= $this->endSection() ?>