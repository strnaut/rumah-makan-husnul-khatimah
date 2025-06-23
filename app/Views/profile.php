<?= $this->extend('layout/page_layout') ?>
<?= $this->section('content') ?>

<style>
    /* Styling Umum */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    .content{
        padding: 50px;
    }

    .profile-container {
        max-width: 700px;
        margin: 0px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        text-align: center; /* Untuk memusatkan konten di dalam container */
    }

    .profile-header {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    .profile-header h1 {
        font-size: 2.5em;
        color: #4CAF50; /* Warna hijau yang konsisten */
        margin-bottom: 10px;
    }

    .profile-header h5 {
        font-size: 1.1em;
        color: #666;
        font-weight: normal;
    }

    /* Styling Form */
    .profile-form-group {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .profile-form-group label {
        flex: 1; /* Label mengambil sebagian ruang */
        text-align: left; /* Teks label rata kiri */
        font-weight: bold;
        color: #555;
        font-size: 1em;
        margin-right: 20px;
    }

    .profile-form-group input[type="text"],
    .profile-form-group input[type="email"] {
        flex: 3; /* Input mengambil lebih banyak ruang */
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 1em;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .profile-form-group input[type="text"]:focus,
    .profile-form-group input[type="email"]:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
        outline: none;
    }

    /* Styling Tombol */
    .button-group {
        margin-top: 30px;
        display: flex;
        justify-content: center; /* Pusatkan tombol */
        gap: 20px; /* Jarak antar tombol */
    }

    .btn-save,
    .btn-logout {
        padding: 12px 25px;
        border: none;
        border-radius: 6px;
        font-size: 1.05em;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        text-decoration: none; /* Untuk tombol logout yang berupa link */
        color: white; /* Warna teks putih */
    }

    .btn-save {
        background-color: #4CAF50; /* Hijau untuk simpan */
    }

    .btn-save:hover {
        background-color: #45a049;
        transform: translateY(-2px);
    }

    .btn-logout {
        background-color: #dc3545; /* Merah untuk logout */
    }

    .btn-logout:hover {
        background-color: #c82333;
        transform: translateY(-2px);
    }
</style>

<body>
    <div class="content">
        <div class="profile-container">
            <div class="profile-header">
                <h1>Profil Saya</h1>
                <h5>Kelola dan lindungi akun Anda</h5>
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
            </div>
            

            <form method="post" action="<?= site_url('profile_update') ?>">
                <div class="profile-form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?= esc($user->username) ?>">
                </div>
                <div class="profile-form-group">
                    <label for="email">Email</label>
                    <input type="text" id="email" name="email" value="<?= esc($user->email) ?>">
                </div>
                <div class="profile-form-group">
                    <label for="phone_number">Nomor Telepon</label>
                    <input type="text" id="phone_number" name="phone_number" value="<?= esc($user->phone_number) ?>">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-save">Simpan</button>
                    <a href="<?= site_url('logout') ?>" class="btn-logout">Keluar</a>
                </div>
            </form>
        </div>
    </div>    
</body>
<?= $this->endSection() ?>