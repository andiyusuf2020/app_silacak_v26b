<?php

namespace App\Libraries;

use TCPDF;

class PdfLibrary extends TCPDF
{
    protected $ci;

    public function __construct()
    {
        parent::__construct();
        $this->ci = service('request');
    }

    // Page header
    public function Header()
    {
        // Set font
        $this->SetFont('helvetica', 'B', 12);
        // Title
        $this->Cell(0, 10, 'LAPORAN LOKASI', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        // Line break
        $this->Ln(10);
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    // Method untuk menambahkan peta
    public function addMap($latitude, $longitude, $address = '', $width = 180, $height = 120)
    {
        // Generate URL Google Maps Static
        $mapUrl = $this->generateMapUrl($latitude, $longitude);

        // Tambahkan judul lokasi
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 10, 'Peta Lokasi', 0, 1, 'L');

        if (!empty($address)) {
            $this->SetFont('helvetica', '', 9);
            $this->MultiCell(0, 8, 'Alamat: ' . $address, 0, 'L');
            $this->Ln(2);
        }

        // Tambahkan koordinat
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 8, 'Koordinat: ' . $latitude . ', ' . $longitude, 0, 1, 'L');
        $this->Ln(3);

        // Simpan gambar peta ke temporary file
        $tempImage = WRITEPATH . 'uploads/map_temp_' . time() . '.jpg';
        $imageData = @file_get_contents($mapUrl);

        if ($imageData) {
            file_put_contents($tempImage, $imageData);

            // Tambahkan gambar ke PDF
            $this->Image($tempImage, 15, $this->GetY(), $width, $height, 'JPEG', '', 'L', false, 300, '', false, false, 0, false, false, false);

            // Hapus file temporary
            unlink($tempImage);
        } else {
            $this->SetFont('helvetica', 'I', 10);
            $this->Cell(0, 40, 'Gambar peta tidak dapat dimuat', 0, 1, 'C');
        }

        $this->Ln($height + 5);
    }

    // Generate Google Maps Static URL
    private function generateMapUrl($latitude, $longitude, $zoom = 15, $size = '600x400')
    {
        $marker = urlencode("color:red|label:L|{$latitude},{$longitude}");
        $apiKey = 'YOUR_GOOGLE_MAPS_API_KEY'; // Ganti dengan API key Anda

        return "https://maps.googleapis.com/maps/api/staticmap?" .
            "center={$latitude},{$longitude}&" .
            "zoom={$zoom}&" .
            "size={$size}&" .
            "markers={$marker}&" .
            "key={$apiKey}";
    }

    // Alternative menggunakan OpenStreetMap (tanpa API key)
    private function generateOSMUrl($latitude, $longitude, $zoom = 15, $width = 600, $height = 400)
    {
        return "https://www.openstreetmap.org/export/embed.html?" .
            "bbox=" . ($longitude - 0.01) . "," . ($latitude - 0.01) . "," .
            ($longitude + 0.01) . "," . ($latitude + 0.01) .
            "&layer=mapnik&marker={$latitude},{$longitude}";
    }

    // Method untuk menggunakan OpenStreetMap (static image)
    public function addOSMMap($latitude, $longitude, $address = '', $width = 180, $height = 120)
    {
        // Gunakan OpenStreetMap via static image service
        $mapUrl = "https://maps.geoapify.com/v1/staticmap?" .
            "style=osm-bright&" .
            "width=600&height=400&" .
            "center=lonlat:{$longitude},{$latitude}&" .
            "zoom=14&" .
            "marker=lonlat:{$longitude},{$latitude};color:%23ff0000;size:medium&" .
            "apiKey=YOUR_GEOAPIFY_API_KEY"; // Dapatkan dari https://www.geoapify.com/

        // Tambahkan judul lokasi
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 10, 'Peta Lokasi (OpenStreetMap)', 0, 1, 'L');

        if (!empty($address)) {
            $this->SetFont('helvetica', '', 9);
            $this->MultiCell(0, 8, 'Alamat: ' . $address, 0, 'L');
            $this->Ln(2);
        }

        // Tambahkan koordinat
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 8, 'Koordinat: ' . $latitude . ', ' . $longitude, 0, 1, 'L');
        $this->Ln(3);

        // Simpan gambar peta ke temporary file
        $tempImage = WRITEPATH . 'uploads/map_temp_' . time() . '.jpg';
        $imageData = @file_get_contents($mapUrl);

        if ($imageData) {
            file_put_contents($tempImage, $imageData);

            // Tambahkan gambar ke PDF
            $this->Image($tempImage, 15, $this->GetY(), $width, $height, 'JPEG', '', 'L', false, 300, '', false, false, 0, false, false, false);

            // Hapus file temporary
            unlink($tempImage);
        } else {
            $this->SetFont('helvetica', 'I', 10);
            $this->Cell(0, 40, 'Gambar peta tidak dapat dimuat', 0, 1, 'C');
        }

        $this->Ln($height + 5);
    }
}
