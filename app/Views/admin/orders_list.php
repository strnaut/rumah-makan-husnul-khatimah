<?php if(in_groups('admin')):?>
<?= $this->extend('layout/page_layout_admin') ?>
<?php else:?>
<?= $this->extend('layout/page_layout') ?>
<?php endif?>
<?= $this->section('content') ?>
<style>
    /* Styling Umum */
    .content-wrapper {
        padding: 20px;
        max-width: 1200px;
        margin: 0px auto;
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
        font-size: 2.2em;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    /* Flash Message */
    .alert {
        padding: 10px;
        border-radius: 5px;
        text-align: center;
        margin: 0px auto 20px auto;
        font-weight: bold;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Filter Container */
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

    /* Tabel Pesanan */
    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden; /* Ensures rounded corners apply to children */
    }

    .order-table th, .order-table td {
        border: 1px solid #ddd;
        padding: 12px 15px;
        text-align: left;
    }

    .order-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #555;
        text-transform: uppercase;
        font-size: 0.9em;
    }

    .order-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .order-table tbody tr:hover {
        background-color: #e9e9e9;
    }

    /* Status Badges */
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8em;
        font-weight: bold;
        color: white;
        text-transform: capitalize;
        display: inline-block;
        min-width: 90px; /* Lebar minimum agar konsisten */
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


    /* Tombol Aksi */
    .btn-detail {
        padding: 8px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.9em;
        transition: background-color 0.2s ease;
    }
    .btn-detail:hover {
        background-color: #0056b3;
    }

    /* No Orders Message */
    .no-orders-message {
        text-align: center;
        padding: 30px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        font-size: 1.1em;
        color: #666;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .content-wrapper {
            padding: 10px;
        }
        .order-table th, .order-table td {
            padding: 8px;
            font-size: 0.8em;
        }
        .filter-container {
            flex-direction: column;
            align-items: flex-start;
        }
        .filter-container select {
            width: 100%;
        }
    }
</style>

<body>
    <div class="content-wrapper">
        <h1>Daftar Pesanan</h1>

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

        <div class="filter-container">
            <label for="filterStatus">Filter Berdasarkan Status Verifikasi:</label>
            <select id="filterStatus" onchange="applyFilter(this.value)">
                <option value="">Semua Status</option>
                <option value="menunggu verifikasi" <?= (isset($_GET['status']) && $_GET['status'] == 'menunggu verifikasi') ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                <option value="terverifikasi" <?= (isset($_GET['status']) && $_GET['status'] == 'terverifikasi') ? 'selected' : '' ?>>Terverifikasi</option>
                <option value="ditolak" <?= (isset($_GET['status']) && $_GET['status'] == 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
            </select>
        </div>

        <?php if (empty($orders)): ?>
            <p class="no-orders-message">Tidak ada pesanan yang sesuai dengan filter saat ini.</p>
        <?php else: ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Harga</th>
                        <th>Status Verifikasi</th>
                        <th>Status Pesanan Item</th> <th>Tanggal Pesan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= esc($order['order_id']) ?></td>
                            <td><?= esc($order['customer_name']) ?></td>
                            <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                            <td>
                                <span class="status-badge status-verification-<?= str_replace(' ', '-', esc($order['verification_status'])) ?>">
                                    <?= esc($order['verification_status']) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($order['items'])): ?>
                                    <span class="status-badge status-item-<?= str_replace(' ', '-', esc($order['items'][0]['status'])) ?>">
                                        <?= esc($order['items'][0]['status']) ?>
                                    </span>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td> <td><?= date('d M Y H:i', strtotime($order['order_date'])) ?></td>
                            <td>
                                <a href="<?= site_url('admin/orders_list/detail/' . $order['order_id']) ?>" class="btn-detail">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function applyFilter(status) {
            let url = '<?= base_url('admin/orders_list') ?>';
            if (status) {
                url += '?status=' + status;
            }
            window.location.href = url;
        }
    </script>
</body>
<?= $this->endSection() ?>