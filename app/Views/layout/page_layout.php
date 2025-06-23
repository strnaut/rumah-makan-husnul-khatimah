<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Husnul Khatimah</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/WEBICON.ico">
    
    <style>
        html {
            min-width: 1100px;
            
        }
        
        body {
            font-family: Inter;
            margin: 0;
            padding: 0;
            text-decoration: none;
            -webkit-text-size-adjust: none;
        }

        .main-content {
            min-height: 83vh;
            background-color: #f4f4f4;
            
        }

        
        .nav-bar a.active-link {
            font-weight: bold; 
            color:rgb(255, 255, 255); 
            border-bottom: 2px solid rgb(255, 255, 255);
            padding-bottom: 3px; 
        }
       
        .nav-bar a {
            transition: all 0.3s ease; 
        }
        .nav-bar a:hover {
            color: rgb(255, 234, 234); 
        }

    </style>
    <link rel="stylesheet" href="<?= base_url('css/cssheaderfooter.css') ?>" />
</head>

<body>
    <div class="header">
        <div class="qweqwe">
            <a href=<?= site_url('/') ?>>
                <img class="logo" src="/logo header.png" height=55px />
            </a>
            <div class="nav-bar">
                <a class="home <?= uri_string() == '' ? 'active-link' : '' ?>" href=<?= site_url('/') ?>>Home</a>
                <a class="product <?= uri_string() == 'product' || strpos(uri_string(), 'product/') === 0 ? 'active-link' : '' ?>" href="<?= site_url('product') ?>">Menu</a>
                <a class="myorder <?= uri_string() == 'orders' || strpos(uri_string(), 'orders/') === 0 ? 'active-link' : '' ?>" href="<?= site_url('orders') ?>">Pesananku</a>
            </div>
            <div class="okok">
                <?php if(in_groups('user')):?>
                    <a href=<?= site_url('/cart') ?> class="<?= uri_string() == 'cart' || strpos(uri_string(), 'cart/') === 0 ? 'active-link' : '' ?>">
                        <img src="/cart.png" height="30px" width="30px">
                    </a>
                <?php endif ?>
                <a href=<?= site_url('/profile') ?> class="<?= uri_string() == 'profile' || strpos(uri_string(), 'profile/') === 0 ? 'active-link' : '' ?>">
                    <img src="/profile picture.png" height="40px" width="40px">
                </a>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <?= $this->renderSection('content') ?>
    </div>

    <footer class="footer">
        <a>Rumah Makan Prasmanan Husnul Khatimah</a>
    </footer>
</body>

</html>