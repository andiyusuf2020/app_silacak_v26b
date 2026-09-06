<!doctype html>
<html lang="en">

<head>
    <script>
        (function(w, i, g) {
            w[g] = w[g] || [];
            if (typeof w[g].push == 'function') w[g].push(i)
        })
        (window, 'G-SEKJ4E9T4H', 'google_tags_first_party');
    </script>
    <script async src="https://preview.colorlib.com/s9cc/"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('set', 'developer_id.dYzg1YT', true);
        gtag('config', 'G-SEKJ4E9T4H');
    </script>

    <title>SILACAK:LOGIN</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://preview.colorlib.com/theme/bootstrap/login-form-20/css/style.css">

</head>

<!-- <body class="img js-fullheight" style="background-image: url(<?= base_url() ?>csslogin/bg1.jpg);"> -->

<body class="img js-fullheight" style="background-image: url(<?= base_url() ?>csslogin/bg_logins.jpg);">

    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-6 text-center mb-5">
                    <!-- <h2 class="heading-section">SiTAPIS</h2> -->
                    <img class="img-fluid" style="max-height: 200px;" src="<?php echo base_url('cssportal/img_home'); ?>/silacak3a.png" alt data-pagespeed-url-hash="2410369107" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">

                    <?= view('App\Views\Auth\_message_block') ?>

                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <form action="<?= url_to('login') ?>" class="signin-form" method="post" autocomplete="off">
                            <?= csrf_field() ?>
                            <?php if ($config->validFields === ['email']): ?>
                                <input type="email" name="login" class="form-control" placeholder="Username" required="required">
                            <?php else: ?>
                                <input type="text" name="login" class="form-control" placeholder="Username" required="required">
                            <?php endif; ?>
                            <!-- <div class="form-group">
                                <input type="text" class="form-control" placeholder="Username" required>
                            </div> -->
                            <div class="form-group">
                                <!-- <input type="password" name="password" required="required"> -->
                                <input id="password-field" type="password" class="form-control" name="password" placeholder="Password" required>
                                <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary submit px-3">Sign In</button>
                            </div>
                            <div class="form-group d-md-flex">
                                <div class="w-50">
                                    <label class="checkbox-wrap checkbox-primary">Remember Me
                                        <input type="checkbox" checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="w-50 text-md-right">
                                    <?php if ($config->activeResetter): ?>
                                        <a href="<?= url_to('forgot') ?>" style="color: #fff">Forgot Password</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </form>
                        <?php if ($config->allowRegistration) : ?>
                            <p class="w-100 text-center">
                                &mdash; <a href="<?= url_to('register') ?>" style="color: #fff">Registrasi</a>
                                &mdash;</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://preview.colorlib.com/theme/bootstrap/login-form-20/js/jquery.min.js"></script>
    <script src="https://preview.colorlib.com/theme/bootstrap/login-form-20/js/popper.js"></script>
    <script src="https://preview.colorlib.com/theme/bootstrap/login-form-20/js/bootstrap.min.js"></script>
    <script src="https://preview.colorlib.com/theme/bootstrap/login-form-20/js/main.js"></script>

    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"966a81d82d49ce25","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"version":"2025.7.0","token":"cd0b4b3a733644fc843ef0b185f98241"}' crossorigin="anonymous"></script>
</body>

</html>