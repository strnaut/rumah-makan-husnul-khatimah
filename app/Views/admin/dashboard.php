<?= $this->extend('layout/page_layout_admin')?>
<?= $this->section('content')?>
<style>
    .dashboard-cards {
        display: flex;
        justify-content: space-around;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }
    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 25px;
        text-align: center;
        flex: 1;
        min-width: 220px;
    }
    .card h3 {
        color: #555;
        font-size: 1.1em;
        margin-bottom: 10px;
    }
    .card p {
        font-size: 2em;
        font-weight: bold;
        color: #333;
        margin: 0;
    }
    .chart-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 25px;
        margin-top: 20px;
    }
    .chart-container h3 {
        text-align: center;
        color: #555;
        margin-bottom: 20px;
    }
    .export-section {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 25px;
        margin-top: 30px;
        margin-bottom: 30px;
    }
    .export-section h3 {
        text-align: center;
        color: #555;
        margin-bottom: 20px;
    }
    .export-form {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
        align-items: flex-end; /* Align items to the bottom */
    }
    .export-form .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 0; /* Remove default form-group margin */
    }
    .export-form label {
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }
    .export-form input[type="date"],
    .export-form button {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .export-form button {
        background-color: #28a745;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .export-form button:hover {
        background-color: #218838;
    }
    .flash-message {
        padding: 10px;
        margin-top: 15px;
        border-radius: 4px;
        text-align: center;
    }
    .flash-message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .flash-message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<h1 style="margin-bottom: 20px;">Dashboard Admin</h1>

<div class="export-section">
    <h3>Ekspor Laporan Penjualan</h3>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="flash-message success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="flash-message error"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <form action="<?= base_url('admin/exportReport') ?>" method="get" class="export-form">
        <div class="form-group">
            <label for="start_date">Dari Tanggal:</label>
            <input type="date" id="start_date" name="start_date" required>
        </div>
        <div class="form-group">
            <label for="end_date">Sampai Tanggal:</label>
            <input type="date" id="end_date" name="end_date" required>
        </div>
        <button type="submit">Export ke Excel</button>
    </form>
</div>

<div class="dashboard-cards">
    <div class="card">
        <h3>Pemasukan Hari Ini</h3>
        <p>Rp. <?= number_format($dailyRevenue, 0, ',', '.') ?></p>
    </div>
    <div class="card">
        <h3>Transaksi Hari Ini</h3>
        <p><?= number_format($dailyTransactions, 0, ',', '.') ?></p>
    </div>
    <div class="card">
        <h3>Pemasukan Bulan Ini</h3>
        <p>Rp. <?= number_format($monthlyRevenue, 0, ',', '.') ?></p>
    </div>
    <div class="card">
        <h3>Transaksi Bulan Ini</h3>
        <p><?= number_format($monthlyTransactions, 0, ',', '.') ?></p>
    </div>
</div>

<div class="chart-container">
    <h3>Grafik Pendapatan Bulanan</h3>
    <canvas id="monthlyRevenueChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyRevenueChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?= json_encode($chartData) ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return 'Rp. ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += 'Rp. ' + context.parsed.y.toLocaleString('id-ID');
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
<?= $this->endsection()?>