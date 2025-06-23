<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/png" href="/WEBICON.ico">

    <title>Login</title>
    <link rel="stylesheet" href="<?= base_url('css/cssauth.css') ?>" />
</head>
<body>
	<div class="container">
        <div class="coloumn left">
            <div class="logo" >
                <a href=<?= site_url('/') ?>>
                    <img src="/Logo header.png"/>
                </a>
            </div>
            <h1 class="login">Masuk</h1>
            <h2>Untuk Lanjut ke Web RM. Husnul Khatimah</h2>
            <p>Rumah Makan Prasmanan Husnul Khatimah - Pejaten Barat</p>
        </div>
        <div class="coloumn right">
            <div class="card">
                <div class="card-body">

                    <?= view('Myth\Auth\Views\_message_block') ?>

                    <form action="<?= url_to('login') ?>" method="post">
                        <?= csrf_field() ?>

                    <?php if ($config->validFields === ['email']): ?>
                        <div class="form-group">
                            <input type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                                name="login" placeholder="<?=lang('Auth.email')?>">
                            <div class="invalid-feedback">
                                <?= session('errors.login') ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                                name="login" placeholder="<?=lang('Auth.emailOrUsername')?>">
                            <div class="invalid-feedback">
                                <?= session('errors.login') ?>
                            </div>
                        </div>
                    <?php endif; ?>

                        <div class="form-group">
                            <input type="password" name="password" class="form-control  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?=lang('Auth.password')?>">
                            <div class="invalid-feedback">
                                <?= session('errors.password') ?>
                            </div>
                        </div>

                    <?php if ($config->allowRemembering): ?>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')) : ?> checked <?php endif ?>>
                                <?=lang('Auth.rememberMe')?>
                            </label>
                        </div>
                    <?php endif; ?>
                    <?php if ($config->allowRegistration) : ?>
                        <div class="qweq">
                            <p>Belum Punya Akun?</p>
                            <a href=<?= site_url('/register')?>>Daftar</a>
                        </div>
                    <?php endif; ?>
                        <button type="submit" class="btn btn-primary btn-block"><?=lang('Auth.loginAction')?></button>
                    </form>
                    <br>
                    <?php if ($config->activeResetter): ?>
                        <p><a href="<?= url_to('forgot') ?>"><?=lang('Auth.forgotYourPassword')?></a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
	</div>
</body>


