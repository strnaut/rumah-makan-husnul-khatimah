<?= $this->extend('layout/page_layout_admin')?>
<?= $this->section('content')?>
<style>
    h1 {
        margin-bottom: 20px;
        color: #333;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .action-buttons a {
        display: inline-block;
        padding: 5px 10px;
        margin-right: 5px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
    }
    .action-buttons .edit-btn {
        background-color: #007bff;
    }
    .action-buttons .delete-btn {
        background-color: #dc3545;
    }
    .flash-message {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
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

<h1>Daftar Pengguna</h1>

<?php if(session()->getFlashdata('success')): ?>
    <div class="flash-message success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="flash-message error"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Nomor Telepon</th>
            <th>Grup</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?= esc($user->id) ?></td>
                <td><?= esc($user->username) ?></td>
                <td><?= esc($user->email) ?></td>
                <td><?= esc($user->phone_number) ?></td>
                <td><?= esc($user->group_name) ?></td>
                <td class="action-buttons">
                    <a href="<?= base_url('admin/users/edit/' . $user->id) ?>" class="edit-btn">Edit</a>
                    <a href="<?= base_url('admin/users/delete/' . $user->id) ?>" class="delete-btn" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Tidak ada pengguna terdaftar.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection()?>