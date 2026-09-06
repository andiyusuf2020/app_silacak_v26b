<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::maintenis');

// Routes for the application SILACAK.

$routes->get('/', 'Silacak\Home::index');
$routes->get('dashboard', 'Silacak\Home::dashboard');
$routes->get('pilihakses', 'Silacak\Home::pilihakses');
$routes->post('simpantahunsilacak', 'Silacak\Home::simpantahunsilacak');
$routes->get('user', 'Silacak\Home::dilarang');

/*---  ROUTE SILACAK LRFK OPD TUBA --------------------- */
$routes->group('rfkopd', ['filter' => 'role:superadmin'], function ($routes) {
    $routes->get('', 'Silacak\HomeRfk::index');
    // $routes->get('', 'Home::adbang');
    $routes->get('datarfk', 'Silacak\HomeRfk::datarfk');
    $routes->get('cetak', 'Silacak\HomeRfk::cetak');
    $routes->post('simpanrfk', 'Silacak\HomeRfk::simpanprfk');
    $routes->get('profile', 'Silacak\HomeRfk::profile');
    $routes->post('simpanprofile', 'Silacak\HomeRfk::simpanprofile');
});
/*---  ROUTE PORTAL LRFK ADMIN --------------------- */
$routes->group('superadmin', ['filter' => 'role:superadmin'], function ($routes) {
    //$routes->group('lrfkadmin',  function ($routes) {
    // $routes->get('', 'Home::maintenis');

    $routes->get('', 'Silacak\SuperadminController::index');
    $routes->get('jadwal', 'LrfkController\AdminLrfkController::index');
    $routes->get('jadwaladmin', 'LrfkController\AdminLrfkController::jadwal');

    //Manajemen User Sitapis Admin Adbang
    $routes->get('daftaruser', 'UserController\UserSitapisController::index');
    $routes->get('manajemenuser', 'UserController\UserSitapisController::user');
    $routes->post('setpassword', 'UserController\UserSitapisController::setPassword');
    $routes->post('groupset', 'UserController\UserSitapisController::changeGroup');
    $routes->post('ubahopdprov', 'UserController\UserSitapisController::simpanubahopd');

    //manajemen data master
    $routes->get('uploadapbd', 'AdminAdbang\ExcelUploadRApbd::index');
    $routes->post('uploadapbd/upload', 'AdminAdbang\ExcelUploadRApbd::upload');
});
$routes->group('adminprov', ['filter' => 'role:adminprov'], function ($routes) {
    $routes->get('', 'AdminAdbang\adminadbang::index');
    $routes->get('uploadapbd', 'AdminAdbang\ExcelUploadRApbd::index');
    $routes->post('uploadapbd/upload', 'AdminAdbang\ExcelUploadRApbd::upload');

    $routes->get('uploadsipd', 'AdminAdbang\ExcelUploadRSipd::index');
    $routes->post('uploadrsipd/upload', 'AdminAdbang\ExcelUploadRSipd::upload');

    $routes->get('uploadrealrup', 'AdminAdbang\ExcelUploadRealRup::index');
    $routes->post('uploadrealrup/upload', 'AdminAdbang\ExcelUploadRealRup::upload');

    $routes->get('uploadrup', 'AdminAdbang\ExcelUploadRup::index');
    $routes->post('uploadrup/upload', 'AdminAdbang\ExcelUploadRup::upload');

    $routes->get('uploadpendapatan', 'AdminAdbang\ExcelUploadPend::index');
    $routes->post('uploadpendapatan/upload', 'AdminAdbang\ExcelUploadPend::upload');

    //-------------------------------------------------------------------------------------------------
    ///Route Admin App 2026
    $routes->get('apbdopd', 'AdminAdbang\adminadbang26::index');
    $routes->get('capkin', 'AdminAdbang\adminadbang26::index');
    $routes->get('pendapatan', 'AdminAdbang\adminadbang26::pendapatan');
    $routes->get('angkasapbd', 'AdminAdbang\adminadbang26::angkasapbd');
    $routes->get('realpbj', 'AdminAdbang\adminadbang26::realpbj');
    $routes->get('ruppbj', 'AdminAdbang\adminadbang26::ruppbj');
    $routes->get('laporanapbdopd', 'AdminAdbang\adminadbang26::laporanapbdopd');
});





// end of routes for the application SILACAK.
/*---  ROUTE SILACAK LRFK OPD TUBA --------------------- */





// $routes->get('users', 'Usersx::index');
// $routes->get('users/index', 'Usersx::index');

$routes->get('/', 'Home::appprov');
// $routes->get('/', 'Home::index');
$routes->get('gratech', 'Home::gratech');
// $routes->get('teslogin', 'Home::tesX');

// $routes->get('user', 'Home::user');
$routes->get('user', 'Home::user', ['filter' => 'role:user']);
// $routes->get('ada', 'tes\Home::index');
// $routes->post('ada/close-modal', 'tes\Home::closeModal');

$routes->get('excel-import', 'ExcelImport::index');
$routes->post('excel-import/import', 'ExcelImport::import');

// $routes->get('excel-upload2', 'ExcelUploadApbd::index');
// $routes->post('excel-upload2/upload', 'ExcelUploadApbd::upload');
$routes->get('excel-upload', 'ExcelUpload::index');
// $routes->post('excel-upload2/upload', 'ExcelUpload::upload');



/*---tes hash----- */
// $routes->get('profile/action', 'Profile::action', ['as' => 'profile-action']);
// $routes->get('profile/index', 'Profile::index');

$routes->get('eksekutif', 'Home::index'); //EksekutifController\EksekutifController::index');
$routes->get('eksekutif/capkin', 'Home::eksekutifcapkin'); //EksekutifController\EksekutifController::index');

$routes->get('eksekutif/json', 'EksekutifController\EksekutifController::getLokasiJson');
$routes->get('eksekutif/uploads/lokasi/(:any)', 'EksekutifController\EksekutifController::getImage/$1');



/*---  ROUTE PORTAL APP --------------------- */
$routes->post('simpantahun', 'Home::simpantahun');
//$routes->get('appprov', 'Home::appprov');


/*---  ROUTE PORTAL PROGRAM PRIORITAS --------------------- */
$routes->group('adbang', function ($routes) {
    $routes->get('', 'Home::maintenis');
    // $routes->get('', 'Home::adbang');
    $routes->get('misigub', 'AdbangController\ProgKerjaController::index');
    $routes->get('program_kerja', 'AdbangController\ProgKerjaController::programkerja');
    $routes->get('program_kerja/(:num)', 'AdbangController\ProgKerjaController::programkerja/$1');
    $routes->post('program_kerja/simpan', 'AdbangController\ProgKerjaController::simpanprogkerja');
    $routes->get('arah_kebijakan', 'AdbangController\ProgKerjaController::arahgkebijakan');
    $routes->post('arah_kebijakan/simpan', 'AdbangController\ProgKerjaController::simpanarahkeb');
    $routes->get('opd_prov', 'AdbangController\ProgKerjaController::opdprov');
});

/*---  AKHIR ROUTE PORTAL PROGRAM PRIORITAS --------------------- */
$routes->get('login', 'AuthController::login');
$routes->get('register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');


/*---  ROUTE PORTAL LRFK ADMIN --------------------- */
$routes->group('lrfkadmin', ['filter' => 'role:adminprov'], function ($routes) {
    //$routes->group('lrfkadmin',  function ($routes) {
    // $routes->get('', 'Home::maintenis');

    $routes->get('', 'Home::lrfkadmin');
    $routes->get('jadwal', 'LrfkController\AdminLrfkController::index');
    $routes->get('jadwaladmin', 'LrfkController\AdminLrfkController::jadwal');

    //Manajemen User Sitapis Admin Adbang
    $routes->get('usersitapis', 'UserController\UserSitapisController::index');
    $routes->get('manajemenuser', 'UserController\UserSitapisController::user');
    $routes->post('setpassword', 'UserController\UserSitapisController::setPassword');
    $routes->post('groupset', 'UserController\UserSitapisController::changeGroup');
    $routes->post('ubahopdprov', 'UserController\UserSitapisController::simpanubahopd');
});
$routes->group('adminprov', ['filter' => 'role:adminprov'], function ($routes) {
    $routes->get('', 'AdminAdbang\adminadbang::index');
    $routes->get('uploadapbd', 'AdminAdbang\ExcelUploadRApbd::index');
    $routes->post('uploadapbd/upload', 'AdminAdbang\ExcelUploadRApbd::upload');

    $routes->get('uploadsipd', 'AdminAdbang\ExcelUploadRSipd::index');
    $routes->post('uploadrsipd/upload', 'AdminAdbang\ExcelUploadRSipd::upload');

    $routes->get('uploadrealrup', 'AdminAdbang\ExcelUploadRealRup::index');
    $routes->post('uploadrealrup/upload', 'AdminAdbang\ExcelUploadRealRup::upload');

    $routes->get('uploadrup', 'AdminAdbang\ExcelUploadRup::index');
    $routes->post('uploadrup/upload', 'AdminAdbang\ExcelUploadRup::upload');

    $routes->get('uploadpendapatan', 'AdminAdbang\ExcelUploadPend::index');
    $routes->post('uploadpendapatan/upload', 'AdminAdbang\ExcelUploadPend::upload');

    //-------------------------------------------------------------------------------------------------
    ///Route Admin App 2026
    $routes->get('apbdopd', 'AdminAdbang\adminadbang26::index');
    $routes->get('capkin', 'AdminAdbang\adminadbang26::index');
    $routes->get('pendapatan', 'AdminAdbang\adminadbang26::pendapatan');
    $routes->get('angkasapbd', 'AdminAdbang\adminadbang26::angkasapbd');
    $routes->get('realpbj', 'AdminAdbang\adminadbang26::realpbj');
    $routes->get('ruppbj', 'AdminAdbang\adminadbang26::ruppbj');
    $routes->get('laporanapbdopd', 'AdminAdbang\adminadbang26::laporanapbdopd');
});


$routes->group('lrfkopd', ['filter' => 'role:useropdprov'], function ($routes) {
    // $routes->get('', 'Home::maintenis');
    $routes->get('', 'Home::lrfkopd');
    $routes->get('subkegiatan', 'LrfkController\OpdProvController::subkegiatan');
    $routes->get('progresrealisasi', 'LrfkController\OpdProvController::progresrealisasi');
    $routes->post('profile', 'LrfkController\OpdProvController::updateprofile');
    $routes->get('profile', 'LrfkController\OpdProvController::profile');
    $routes->get('opd', 'LrfkController\OpdProvController::index');
    $routes->get('cetak', 'LrfkController\OpdProvController::cetakPdf');
    $routes->post('simpanprogres', 'LrfkController\OpdProvController::saveprogres');
    $routes->get('kinerja', 'LrfkController\CapKinOpdController::kinerja');
    $routes->post('simpaniku', 'LrfkController\CapKinOpdController::saveindikator');
    $routes->post('simpanindisubkeg', 'LrfkController\CapKinOpdController::saveindisk');
    //data pendapatan
    $routes->get('pendapatan', 'LrfkController\PendapatanOPDController::index');
    $routes->post('simpanpendapatan', 'LrfkController\PendapatanOPDController::simpanpendapatan');


    // data p3dn
    $routes->get('pdn', 'PdnController\p3dnController::index');

    // Route Controller App 2026
    $routes->get('apbdopd', 'LrfkController\OpdProvController26::index');
    $routes->post('simpanperubahan', 'LrfkController\OpdProvController26::saveperubahan');
    $routes->get('pbj', 'LrfkController\OpdProvController26::index');
    $routes->post('simpanpendapatanapbd', 'LrfkController\OpdProvController26::simpanpendapatanapbd');
    $routes->post('simpanangkasapbd', 'LrfkController\OpdProvController26::simpanangkasapbd');

    // $routes->get('apbdopd', 'LrfkController\OpdProvController26::direct');
});

$routes->group('capkin', ['filter' => 'role:useropdprov'], function ($routes) {
    // $routes->get('', 'Home::maintenis');

    $routes->get('', 'CapKinController\CapKin::index');
    $routes->get('kegpokok', 'CapKinController\CapKin::kegpokok');
    $routes->post('simpan', 'CapKinController\CapKin::simpancapkin');
    $routes->post('simpankegpokok', 'CapKinController\CapKin::simpankegpokok');
    $routes->post('simpanrealisasikp', 'CapKinController\CapKin::simpanrealisasikp');
    $routes->post('simpandokumentasi', 'CapKinController\CapKin::simpanlokasi');

    //2026
    // $routes->get('2026', 'CapKinController\CapKin26::index');
});
$routes->group('capkin2026', ['filter' => 'role:useropdprov'], function ($routes) {
    $routes->get('', 'CapKinController\CapKin26::index');
    $routes->post('simpansubkeg', 'CapKinController\CapKin26::simpansubkeg');
    $routes->post('pilihprogram', 'CapKinController\CapKin26::pilihprogram');

    $routes->post('simpanrencanaaktifitas', 'CapKinController\CapKin26::simpanrencanaaktifitas');
    $routes->post('simpanrealisasiaktifitas', 'CapKinController\CapKin26::simpanrealisasiaktifitas');
    $routes->post('simpandokumentasi', 'CapKinController\CapKin26::simpanlokasi');
    $routes->post('simpanpermasalahanaktifitas', 'CapKinController\CapKin26::simpanpermasalahanaktifitas');
});

/*---  ROUTE DESAKU MAJU --------------------- */
$routes->get('desakumaju', 'DesaKuMajuController\Home::index');
$routes->group('desakumaju/opd', ['filter' => 'role:userdesakumaju'], function ($routes) {
    $routes->get('', 'DesaKuMajuController\OpdController::index');
    $routes->get('data', 'DesaKuMajuController\OpdController::data');
    $routes->post('simpanketerlibatan', 'DesaKuMajuController\OpdController::simpanketerlibatan');
});

/*---  AKHIR ROUTE DESAKU MAJU --------------------- */
$routes->get('dekontp', 'DekonTPController\Home::index');

/*---  AKHIR ROUTE PORTAL LRFK --------------------- */
// $routes->group('deepseek', function ($routes) { 
//     $routes->get('/', 'Deepseek::index');
//     $routes->post('ask', 'Deepseek::ask');
//     $routes->get('clear-history', 'Deepseek::clearHistory');
//     $routes->post('api/ask', 'Deepseek::apiAsk');
// });
// $routes->get('/lokasi', 'Lokasi::index');
// $routes->get('/lokasi/tambah', 'Lokasi::tambah');
// $routes->post('/lokasi/simpan', 'Lokasi::simpan');
// $routes->get('/lokasi/detail/(:num)', 'Lokasi::detail/$1');
// $routes->get('/lokasi/edit/(:num)', 'Lokasi::edit/$1');
// $routes->post('/lokasi/update/(:num)', 'Lokasi::update/$1');
// $routes->put('/lokasi/update/(:num)', 'Lokasi::update/$1');
// $routes->get('uploads/lokasi/(:any)', 'Lokasi::getImage/$1');
// $routes->delete('/lokasi/hapus/(:num)', 'Lokasi::hapus/$1');
// $routes->get('/lokasi/json', 'Lokasi::getLokasiJson');
