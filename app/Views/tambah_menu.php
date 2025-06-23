<?= $this->extend('layout/page_layout_admin')?>
<?= $this->section('content')?>
<link rel="stylesheet" href="<?= base_url('vendors/bootstrap/css/bootstrap.min.css') ?>" />
<link rel="stylesheet" href="<?= base_url('vendors/fontawesome/css/all.min.css') ?>" />
<style>
    .container-form {
        max-width: 600px;
        margin: 30px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
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
    .form-group input[type="number"],
    .form-group input[type="file"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1em;
    }
    .input-group {
        display: flex;
        align-items: center;
    }
    .input-group span {
        padding: 10px;
        border: 1px solid #ddd;
        border-right: none;
        border-radius: 5px 0 0 5px;
        background-color: #e9ecef;
        color: #495057;
    }
    .input-group input {
        border-left: none;
        border-radius: 0 5px 5px 0;
    }
    .btn-submit {
        display: block;
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 1.1em;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 20px; /* Jarak dari elemen sebelumnya */
    }
    .btn-submit:hover {
        background-color: #0056b3;
    }
    .btn-back {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 15px;
        background-color: #6c757d; /* Warna abu-abu */
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .btn-back:hover {
        background-color: #5a6268;
    }
</style>

<div class="container-form">
    <h1>Tambah Menu Baru</h1>
    <?php if(session()->getFlashdata('errors')): ?>
        <div style="color:red;">
            <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <form action="<?= site_url('/product/store_product'); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Nama Menu</label>
            <input type="text" id="product_name" name="product_name" class="form-control" value="<?= old('product_name') ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Harga</label>
            <div class="input-group">
                <span>Rp.</span>
                <input type="number" id="price" name="price" class="form-control" value="<?= old('price') ?>" required min="0">
            </div>
        </div>
        <div class="form-group">
            <label for="stock">Stok</label>
            <input type="number" id="stock" name="stock" class="form-control" value="<?= old('stock') ?>" required min="0">
        </div>
        <div class="form-group">
            <label for="photo">Foto Menu</label>
            <input type="file" id="photo" name="photo" class="form-control-file" accept="image/*" required>
        </div>
        <button type="submit" class="btn-submit">Submit</button>
    </form>
    <a href="<?= site_url('product') ?>" class="btn-back">Kembali ke Daftar Menu</a>
</div>

<?= $this->endsection()?>