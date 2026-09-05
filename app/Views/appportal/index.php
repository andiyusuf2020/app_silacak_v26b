<!-- DEBUG-VIEW START 1 APPPATH/Views/home/home.php -->
<!DOCTYPE html>
<html lang="en">
<!-- head -->

<head>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <title>SiTAPIS Pemerintah Provinsi Lampung</title>
    <link rel="icon" href="<?php echo base_url(); ?>/favicon.ico" type="image/gif">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('cssportal'); ?>/costom5.css" media="all">
    <style>
        #intro-video-container .caption h2 {
            margin-top: -35px
        }

        .pelengkap-list {
            visibility: hidden;
            width: 0;
            display: none
        }

        .title-mobile {
            visibility: hidden;
            display: none
        }

        @media (max-width:500px) {
            .pelengkap-list {
                visibility: visible
            }

            .perahu {
                visibility: hidden;
                width: 0;
                display: none
            }

            .lambung-kapal {
                margin-right: 0 !important;
                padding-right: 15px !important
            }

            .title-mobile {
                visibility: visible;
                display: block;
                left: 5%;
                color: #fff;
                position: absolute;
                bottom: 0;
                right: 5%;
                font-size: 1.25rem;
                text-transform: uppercase
            }

            .cpanel-item .icon img {
                top: 30%
            }
        }

        #intro-video-container {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat
        }

        #intro-video-container .enter-btn {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 50px;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
            z-index: 999;
            background: #c0392b;
            text-decoration: none;
            border: 2px solid #fff
        }

        #intro-video-container:before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            background: rgba(0, 0, 0, .4);
            z-index: 99;
            overflow: auto
        }

        #intro-video {
            position: fixed;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1;
            transform: translateX(-50%) translateY(-50%);
            background-size: cover;
            transition: 1s opacity
        }

        #intro-video-container .caption {
            position: absolute;
            top: 50px;
            left: 50%;
            color: #fff;
            z-index: 99;
            width: 780px;
            transform: translateX(-50%);
            -webkit-transform: translateX(-50%);
            text-align: center
        }

        #intro-video-container .caption h2 {
            font-weight: bold;
            font-size: 30px;
            margin: 0
        }

        #intro-video-container .caption p {
            font-size: 20px;
            font-weight: 200
        }

        .intro-searchform {
            width: 400px;
            max-width: 100%;
            margin: 0 auto 30px;
            background: rgba(255, 255, 255, .9);
            border: 1px solid rgba(255, 255, 255, .8);
            border-radius: 100px;
            overflow: hidden
        }

        .intro-searchform .form-control {
            border: none;
            background: none;
            color: #fff;
            font-weight: bold
        }

        .intro-searchform .btn {
            border-radius: 50% !important;
            padding: 0;
            width: 30px;
            height: 30px;
            line-height: 30px;
            margin: 2px
        }
    </style>
</head>

<body>

    <!-- Content
	============================================= -->
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-md-6 col-sm-12 text-center">
                <div role="alert">
                    <a class="alert-link" href="#">
                        <img class="img-fluid" style="max-height: 200px;" src="<?php echo base_url('cssportal/img_home'); ?>/gub_rmd.png" alt data-pagespeed-url-hash="2622387745" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 text-center">
                <div role="alert">
                    <a class="alert-link" href="#">
                        <img class="img-fluid" style="max-height: 200px;" src="<?php echo base_url('cssportal/img_home'); ?>/wagubJihan.png" alt data-pagespeed-url-hash="2410369107" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">

                    </a>
                </div>
            </div>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-md-6 col-sm-12 text-center">
                <div role="alert">
                    <a class="alert-link" href="<?php echo base_url(); ?>" <img class="img-fluid" style="max-height: 175px;" src="<?php echo base_url('admin/dist'); ?>/img/logositapis.png" alt>
                        <!-- <h3 class="header-title">
                            <font color="yellow">
                                <p> E-TAPIS
                            </font>
                        </h3> -->
                        <img class="img-fluid" style="max-height: 200px;" src="<?php echo base_url('cssportal/img_home'); ?>/Biro_Adbang.png" alt data-pagespeed-url-hash="2410369107" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
                        <img class="img-fluid" style="max-height: 200px;" src="<?php echo base_url('cssportal/img_home'); ?>/Sitapis.png" alt data-pagespeed-url-hash="2410369107" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
                    </a>
                </div>
            </div>
        </div>
        <!--    /row-->
        <div class="row mt-2" id="kotakkategori">
            <div class="col">
                <!-- contenrow -->
                <ul class="d-flex justify-content-center flex-wrap">
                    <li>
                    </li>
                    <li>
                        <a class="normal" style="cursor: pointer;" href="<?php echo base_url('login');
                                                                            ?>">
                            <img src="<?php echo base_url('cssportal/img_home');
                                        ?>/Sitapis.png" alt>
                            <div class="caption"><strong>LOGIN</strong> </div>
                        </a>
                    </li>
                    <li>
                    </li>
                    <!-- <li>
                        <a class="normal" style="cursor: pointer;" href="<?php //echo base_url('appprov'); 
                                                                            ?>">
                            <img src="<?php //echo base_url('cssportal/img_home'); 
                                        ?>/e-Tapis.gif" alt>
                            <div class="caption"><strong>4. AGENDA KERJA UTAMA</strong> </div>
                        </a>
                    </li> -->
                </ul>
            </div>
        </div>
    </div>
    <video id="intro-video" playsinline autoplay muted loop poster="#">
        <source src="https://adbang.lampungprov.go.id/sitapis/uploads/lampung(1).mp4" type="video/mp4">
    </video>
    <center><audio controls autoplay loop>
            <source src="https://adbang.lampungprov.go.id/sitapis/uploads/tunggal_lampung(1).mp3" type="audio/ogg">
        </audio></center>

</html>
<!-- DEBUG-VIEW ENDED 1 APPPATH/Views/home/home.php -->