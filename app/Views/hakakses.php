<div class="flex flex-col items-center justify-center w-screen h-screen gap-12 py-8 ">
    <div class="flex flex-col items-center gap-4">
        <h1 class="text-3xl font-medium text-center">
            <?php
            $wilayah = session()->get('wilayah');

            echo $wilayah;
            ?>
            <?= esc($title1) ?>
        </h1>
        <p class="text-x2 text-center ">
            Anda mencoba mengakses halaman yang tidak diizinkan, silakan hubungi administrator atau
            <a href="<?= esc(base_url('logout')) ?>" class="text-blue-500 hover:underline">logout</a> dari akun Anda.
            Jika Anda yakin ini adalah kesalahan, silakan hubungi tim dukungan kami untuk bantuan lebih lanjut.
        </p>
        <h1 class="text-3xl font-medium text-center">
            <a href="<?= esc(base_url('logout')) ?>" class="text-blue-500 hover:underline">logout</a>
        </h1>
    </div>
</div>