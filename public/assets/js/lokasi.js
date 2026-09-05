document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi peta
    // const map = L.map('map').setView([-2.5489, 118.0149], 5); // Posisi awal Indonesia
          const map = L.map('map').setView([-4.4254, 102.2580], 5); // Posisi awal Indonesia
  
    // Tambahkan layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'SiTAPIS : Sistem Data Pengendalian dan Informasi'
    }).addTo(map);
    
    // Variabel untuk marker
    let marker = null;
    
    // Event klik pada peta
    map.on('click', function(e) {
        updateMarker(e.latlng.lat, e.latlng.lng);
    });
    
    // Fungsi untuk update marker dan input koordinat
    function updateMarker(lat, lng) {
        // Update input latitude dan longitude
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        // Hapus marker sebelumnya jika ada
        if (marker) {
            map.removeLayer(marker);
        }
        
        // Tambahkan marker baru
        marker = L.marker([lat, lng], {draggable: true}).addTo(map)
            .bindPopup(`Lokasi terpilih:<br>Lat: ${lat}<br>Lng: ${lng}`)
            .openPopup();
        
        // Event saat marker di-drag
        marker.on('dragend', function(e) {
            const newLatLng = e.target.getLatLng();
            document.getElementById('latitude').value = newLatLng.lat;
            document.getElementById('longitude').value = newLatLng.lng;
        });
    }
    
    // Tombol "Gunakan Lokasi Saya"
    document.getElementById('lokasi-sekarang').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                updateMarker(lat, lng);
                map.setView([lat, lng], 15);
            }, function(error) {
                alert('Gagal mendapatkan lokasi: ' + error.message);
            });
        } else {
            alert('Browser tidak mendukung geolokasi');
        }
    });
    
    // Tombol "Cari dengan Koordinat"
    document.getElementById('cari-koordinat').addEventListener('click', function() {
        const lat = parseFloat(prompt('Masukkan latitude:'));
        const lng = parseFloat(prompt('Masukkan longitude:'));
        
        if (!isNaN(lat) && !isNaN(lng)) {
            updateMarker(lat, lng);
            map.setView([lat, lng], 15);
        } else {
            alert('Koordinat tidak valid');
        }
    });
    
    // Tombol "Cari dengan Alamat" (Geocoding menggunakan Nominatim)
    document.getElementById('cari-alamat').addEventListener('click', function() {
        const alamat = prompt('Masukkan alamat:');
        
        if (alamat) {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(alamat)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const firstResult = data[0];
                        const lat = parseFloat(firstResult.lat);
                        const lng = parseFloat(firstResult.lon);
                        
                        updateMarker(lat, lng);
                        map.setView([lat, lng], 15);
                        
                        // Isi alamat jika kosong
                        if (!document.getElementById('alamat').value) {
                            document.getElementById('alamat').value = firstResult.display_name;
                        }
                    } else {
                        alert('Alamat tidak ditemukan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mencari alamat');
                });
        }
    });
    
    // Jika ada nilai latitude dan longitude di form (misal saat validasi gagal)
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    
    if (latInput.value && lngInput.value) {
        updateMarker(parseFloat(latInput.value), parseFloat(lngInput.value));
        map.setView([latInput.value, lngInput.value], 15);
    }
    
    // Handle kategori baru
    const kategoriSelect = document.getElementById('kategori');
    const kategoriBaruInput = document.getElementById('kategori_baru');
    
    kategoriBaruInput.addEventListener('input', function() {
        if (this.value) {
            kategoriSelect.value = '';
        }
    });
    
    kategoriSelect.addEventListener('change', function() {
        if (this.value) {
            kategoriBaruInput.value = '';
        }
    });
});