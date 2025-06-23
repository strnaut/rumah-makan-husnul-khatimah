<?= $this->extend('layout/page_layout') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('css/csslandingpage.css') ?>" />

<body>
    <div class="welcome-aboard">
        <h1>SELAMAT DATANG!</h1>
        <svg class="wave" style="transform:rotate(180deg); transition: 0.3s" viewBox="0 0 1440 100" version="1.1" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="sw-gradient-0" x1="0" x2="0" y1="1" y2="0">
                    <stop stop-color="#8bc34a" offset="0%"></stop>
                    <stop stop-color="#8bc34a" offset="100%"></stop>
                </linearGradient>
            </defs>
            <path style="transform:translate(0, 0px); opacity:1" fill="url(#sw-gradient-0)" d="M0,40L60,46.7C120,53,240,67,360,73.3C480,80,600,80,720,71.7C840,63,960,47,1080,43.3C1200,40,1320,50,1440,58.3C1560,67,1680,73,1800,70C1920,67,2040,53,2160,43.3C2280,33,2400,27,2520,23.3C2640,20,2760,20,2880,26.7C3000,33,3120,47,3240,43.3C3360,40,3480,20,3600,16.7C3720,13,3840,27,3960,41.7C4080,57,4200,73,4320,76.7C4440,80,4560,70,4680,68.3C4800,67,4920,73,5040,73.3C5160,73,5280,67,5400,68.3C5520,70,5640,80,5760,70C5880,60,6000,30,6120,28.3C6240,27,6360,53,6480,60C6600,67,6720,53,6840,53.3C6960,53,7080,67,7200,60C7320,53,7440,27,7560,23.3C7680,20,7800,40,7920,46.7C8040,53,8160,47,8280,50C8400,53,8520,67,8580,73.3L8640,80L8640,100L8580,100C8520,100,8400,100,8280,100C8160,100,8040,100,7920,100C7800,100,7680,100,7560,100C7440,100,7320,100,7200,100C7080,100,6960,100,6840,100C6720,100,6600,100,6480,100C6360,100,6240,100,6120,100C6000,100,5880,100,5760,100C5640,100,5520,100,5400,100C5280,100,5160,100,5040,100C4920,100,4800,100,4680,100C4560,100,4440,100,4320,100C4200,100,4080,100,3960,100C3840,100,3720,100,3600,100C3480,100,3360,100,3240,100C3120,100,3000,100,2880,100C2760,100,2640,100,2520,100C2400,100,2280,100,2160,100C2040,100,1920,100,1800,100C1680,100,1560,100,1440,100C1320,100,1200,100,1080,100C960,100,840,100,720,100C600,100,480,100,360,100C240,100,120,100,60,100L0,100Z"></path>
        </svg>
    </div>

    <div class="content">
        <h1>Menu Favorit</h1>
        <p>Cobalah Menu Favorit di Rumah Makan Kami</p>
    </div>

    <div class="ex-img">
        <a href="" class="pokpok">
            <img src="https://i.gojekapi.com/darkroom/gofood-indonesia/v2/images/uploads/1a5d3bda-c0b1-4cb9-aa9a-64c9901b7009_Go-Biz_20220216_112404.jpeg?auto=format" />
            <p class="product-name">Tongkol Balado</p>
            <p class="price">Rp. 7.000</p>
        </a>
        <a href="" class="pokpok">
            <img src="/pecaknila.png" />
            <p class="product-name">Pecak Nila</p>
            <p class="price">Rp. 18.000</p>
        </a>
        <a href="" class="pokpok">
            <img src="/garangasem.png" />
            <p class="product-name">Garang Asem</p>
            <p class="price">Rp. 17.000</p>
        </a>
    </div>

    <div class="shopping-button">
        <a href=<?= site_url('/product') ?>>Start Shopping</a>
    </div>

</body>

<?= $this->endSection() ?>