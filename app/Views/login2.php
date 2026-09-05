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
                    <form autocomplete="off">
                        <h2>Sign in</h2>
                        <div class="inputBox">
                            <input type="text" required="required">
                            <span>Userame</span>
                            <i></i>
                        </div>
                        <div class="inputBox">
                            <input type="password" required="required">
                            <span>Password</span>
                            <i></i>
                        </div>
                        <div class="links">
                            <a href="#">Forgot Password ?</a>
                            <a href="#">Signup</a>
                        </div>
                        <input type="submit" value="Login">
                    </form>
                </div>
            </td>
            <td>
                <div class="box">
                    <form autocomplete="off">

                        <h2>Informasi!!</h2>
                        <p>Tahapan Pendaftaran User Untuk Mengakses Data dan Informasi;</p>
                        <p>1>>. Klik Sign Up,isi dan lengkapi data yang diminta</p>
                        <p>2>>. Klik link aktivasi yang dikirim ke email yang didaftarkan</p>
                        <p>3>>. Hubungi Helpdesk SiTAPIS Biro Administrasi Pembangunan Setda Provinsi Lampung
                            untuk aktivasi pendaftaran di WhatsApp 081279027369</p>
                        <p><a class="btn btn-lg btn-outline-light mt-3" href="#">Dashboard SiTAPIS</a></p>
                    </form>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>