<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/WEBICON.ico">
    
    <style>
        /* Reset dan dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            background-color: #f4f4f4;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color:rgb(68, 156, 71);
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }
        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 12px 10px;
            margin-bottom: 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgb(47, 105, 48);
        }
        .logo {
            width: -webkit-fill-available;
            height: -webkit-fill-available;
        }

        /* Main content */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div href=<?= site_url('/') ?>>
            <img class="logo" src="/logo header.png" height=55px />
        </div>
        <br>
        
        <a href="<?= base_url('admin') ?>" class="<?= uri_string() == 'admin' ? 'active' : '' ?>">Dashboard</a>
        <a href="<?= base_url('admin/orders_list') ?>" class="<?= uri_string() == 'admin/orders_list' ? 'active' : '' ?>">Pesanan</a>
        <a href="<?= base_url('product') ?>" class="<?= uri_string() == 'product' ? 'active' : '' ?>">Menu</a>
        <a href="<?= base_url('admin/users') ?>" class="<?= uri_string() == 'admin/users' ? 'active' : '' ?>">User</a>
        <a href="<?= base_url('logout') ?>">Logout</a>
    </div>

    <div class="main-content">
        <?= $this->renderSection('content') ?>
    </div>
</body>
</html>
