<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" type="image/png" href="/WEBICON.ico">

    <title>Register</title>
    <link rel="stylesheet" href="<?= base_url('css/cssauth.css') ?>" />
</head>
<div class="container">
    <div class="coloumn left">
        <div class="logo" >
            <a href=<?= site_url('/') ?>>
                <img src="/Logo header.png"/>
            </a>
        </div>
            <h1 class="login">Daftar</h1>
			<h2>Untuk Lanjut ke Web RM. Husnul Khatimah</h2>
			<p>Rumah Makan Prasmanan Husnul Khatimah - Pejaten Barat</p>
        </div>
                
    <div class="coloumn right">

    <?= view('Myth\Auth\Views\_message_block') ?>

        <form action="<?= url_to('register') ?>" method="post">
        <?= csrf_field() ?>

            <div class="form-group">
                <input type="email" class="form-control <?php if (session('errors.email')) : ?>is-invalid<?php endif ?>"
                name="email" aria-describedby="emailHelp" placeholder="<?=lang('Auth.email')?>" value="<?= old('email') ?>">
            </div>

            <div class="form-group">
                <input type="text" class="form-control <?php if (session('errors.username')) : ?>is-invalid<?php endif ?>" name="username" placeholder="<?=lang('Auth.username')?>" value="<?= old('username') ?>">
            </div>
            
            <div class="form-group">
                <input type="password" name="password" class="form-control <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?=lang('Auth.password')?>" autocomplete="off">
            </div>

            <div class="form-group">
                <input type="password" name="pass_confirm" class="form-control <?php if (session('errors.pass_confirm')) : ?>is-invalid<?php endif ?>" placeholder="<?=lang('Auth.repeatPassword')?>" autocomplete="off">
            </div>

            <div class="qweq">
                <p>Sudah Punya Akun?</p>
                <a href=<?= site_url('/login') ?>>Masuk</a>
            </div>

            <button type="submit" class="btn btn-primary btn-block"><?=lang('Auth.register')?></button>
        </form>             
    </div>
</div>
