<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('silacaklogin') ?>/fonts/icomoon/style.css">

    <link rel="stylesheet" href="<?= base_url('silacaklogin') ?>/css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('silacaklogin') ?>/css/bootstrap.min.css">

    <!-- Style -->
    <link rel="stylesheet" href="<?= base_url('silacaklogin') ?>/css/style.css">

    <title>Login #7</title>
</head>

<body>



    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?= base_url('silacaklogin') ?>/images/undraw_remotely_2j6y.svg" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-6 contents">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h3>Sign In</h3>
                                <?= view('App\Views\Auth\_message_block') ?>

                                <p class="mb-4">Lorem ipsum dolor sit amet elit. Sapiente sit aut eos consectetur adipisicing.</p>
                            </div>
                            <form action="<?= url_to('login') ?>" method="post">
                                <?= csrf_field() ?>
                                <!-- <div class="form-group first"> -->
                                <?php if ($config->validFields === ['email']): ?>
                                    <div class="form-group">
                                        <label for="login"><?= lang('Auth.email') ?></label>
                                        <input type="email" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                                            name="login" placeholder="">
                                        <div class="invalid-feedback">
                                            <?= session('errors.login') ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group">
                                        <label for="login"><?= lang('Auth.emailOrUsername') ?></label>
                                        <input type="text" class="form-control <?php if (session('errors.login')) : ?>is-invalid<?php endif ?>"
                                            name="login" placeholder="">
                                        <div class="invalid-feedback">
                                            <?= session('errors.login') ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- </div> -->
                                <div class="form-group last mb-4">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control  <?php if (session('errors.password')) : ?>is-invalid<?php endif ?>" placeholder="<?= lang('Auth.password') ?>">
                                    <div class="invalid-feedback">
                                        <?= session('errors.password') ?>
                                    </div>

                                </div>
                                <?php if ($config->allowRemembering): ?>
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')) : ?> checked <?php endif ?>>
                                            <?= lang('Auth.rememberMe') ?>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <!-- <div class="d-flex mb-5 align-items-center">
                                    <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                                        <input type="checkbox" checked="checked" />
                                        <div class="control__indicator"></div>
                                    </label>
                                    <span class="ml-auto"><a href="#" class="forgot-pass">Forgot Password</a></span>
                                </div> -->
                                <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.loginAction') ?></button>
                            </form>

                            <!-- <input type="submit" value="Log In" class="btn btn-block btn-primary"> -->

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <script src="<?= base_url('silacaklogin/js/jquery-3.3.1.min.js') ?>"></script>
    <script src="<?= base_url('silacaklogin/js/popper.min.js') ?>"></script>
    <script src="<?= base_url('silacaklogin/js/bootstrap.min.js') ?>"></script>
    <script src="<?= base_url('silacaklogin/js/main.js') ?>"></script>
</body>

</html>