// Inisialisasi peta
const map = L.map('map').setView([-2.5489, 118.0149], 5); // Posisi awal Indonesia

// Tambahkan layer OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Tambahkan marker untuk setiap lokasi
lokasiData.forEach(lokasi => {
    L.marker([lokasi.latitude, lokasi.longitude])
        .addTo(map)
        .bindPopup(`
            <b>${lokasi.nama}</b><br>
            ${lokasi.deskripsi || 'Tidak ada deskripsi'}<br>
            <small>Lat: ${lokasi.latitude}, Lng: ${lokasi.longitude}</small>
        `);
});

// Jika ada lebih dari satu lokasi, sesuaikan view peta
if (lokasiData.length > 0) {
    const group = new L.featureGroup
    (lokasiData.map(lokasi => 
        L.marker([lokasi.latitude, lokasi.longitude])
    ));
    map.fitBounds(group.getBounds().pad(0.5));
}