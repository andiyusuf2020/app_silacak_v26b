<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login SiTAPIS</title>
    <link rel="stylesheet" href="<?php echo base_url('stylelogin') ?>/style.css">
</head>

<body>
    <table>
        <tr>
            <td>
                <div class="box">

                    <form action="<?= url_to('login') ?>" method="post" autocomplete="off">
                        <h2>Sign in</h2>

                        <?= csrf_field() ?>

                        <?php if ($config->validFields === ['email']): ?>
                            <div class="inputBox">
                                <input type="email" name="login" required="required">
                                <span>Userame</span>
                                <i></i>
                            </div>
                        <?php else: ?>
                            <div class="inputBox">
                                <input type="text" name="login" required="required">
                                <span>Userame</span>
                                <i></i>

                            </div>
                        <?php endif; ?>

                        <div class="inputBox">
                            <input type="password" name="password" required="required">

                            <span>Password</span>
                            <i></i>
                        </div>
                        <div class="links">
                            <?php if ($config->allowRegistration) : ?>
                                <p><a href="<?= url_to('register') ?>"><?= lang('Auth.needAnAccount') ?></a></p>
                            <?php endif; ?>
                            <?php if ($config->activeResetter): ?>
                                <p><a href="<?= url_to('forgot') ?>"><?= lang('Auth.forgotYourPassword') ?></a></p>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block"><?= lang('Auth.loginAction') ?></button>
                    </form>
                </div>
            </td>
            <td>
                <div class="box">
                    <form autocomplete="off">
                        <?= view('App\Views\Auth\_message_block') ?>
                    </form>
                </div>
                <div>
                    <img class="img-fluid" style="max-height: 200px;" src="<?php echo base_url('cssportal/img_home'); ?>/silacak3a.png" alt data-pagespeed-url-hash="2410369107" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
                </div>
            </td>
        </tr>
    </table>
</body>

</html>