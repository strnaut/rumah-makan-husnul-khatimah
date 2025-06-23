<?= $this->extend('layout/page_layout_admin')?>
<?= $this->section('content')?>
<style>
    h1 {
        margin-bottom: 20px;
        color: #333;
    }
    form {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        max-width: 500px;
        margin: 0 auto;
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
    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group select {
        width: calc(100% - 22px); /* Adjust for padding and border */
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1em;
    }
    .form-group .error-message {
        color: red;
        font-size: 0.9em;
        margin-top: 5px;
    }
    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1em;
    }
    button[type="submit"]:hover {
        background-color: #0056b3;
    }
    .back-link {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        color: #007bff;
    }
    .back-link:hover {
        text-decoration: underline;
    }
</style>



<?php if(session()->getFlashdata('errors')): ?>
    <div style="color:red;">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div style="color:red;"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<form action="<?= base_url('admin/users/update/' . $user->id) ?>" method="post">
    <?= csrf_field() ?>
    <h1 style="text-align: center;">Edit Pengguna</h1>
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?= old('username', $user->username) ?>" required>
        <?php if(session('errors.username')): ?><p class="error-message"><?= session('errors.username') ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= old('email', $user->email) ?>" required>
        <?php if(session('errors.email')): ?><p class="error-message"><?= session('errors.email') ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="phone_number">Nomor Telepon:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?= old('phone_number', $user->phone_number) ?>">
        <?php if(session('errors.phone_number')): ?><p class="error-message"><?= session('errors.phone_number') ?></p><?php endif; ?>
    </div>

    <div class="form-group">
        <label for="group">Grup:</label>
        <select name="group" id="group" required>
            <?php foreach($groups as $group): ?>
                <option value="<?= esc($group->id) ?>" <?= ($user->group_id == $group->id) ? 'selected' : '' ?>>
                    <?= esc($group->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if(session('errors.group')): ?><p class="error-message"><?= session('errors.group') ?></p><?php endif; ?>
    </div>

    <button type="submit">Simpan Perubahan</button> <br>

    <a href="<?= base_url('admin/users') ?>" class="back-link">Kembali ke Daftar Pengguna</a>
</form>


<?= $this->endSection()?>