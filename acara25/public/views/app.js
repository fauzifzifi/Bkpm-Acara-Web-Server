const BASE_URL = 'http://localhost/acara_25/public';
const aksiAdmin = document.querySelector('#aksi-admin');

if (localStorage.getItem('role') === 'user') {
 aksiAdmin?.remove();
}

async function muatDataMahasiswa() {
 const tbody = document.querySelector('#tabel-mahasiswa tbody');
 tbody.innerHTML = '<tr><td colspan="3">Memuat data...</td></tr>';

 try {
 const response = await fetch(`${BASE_URL}/mahasiswa`);
 const hasil = await response.json();
 tbody.innerHTML = '';

 if (hasil.data.length === 0) {
 tbody.innerHTML = '<tr><td colspan="3">Belum ada data</td></tr>';
 return;
 }

 hasil.data.forEach((mhs) => {
 tbody.innerHTML +=
`<tr><td>${mhs.nim}</td><td>${mhs.nama}</td><td>${mhs.prodi}</td></tr>`;
 });
 } catch (error) {
 console.error('Gagal memuat data:', error);
 }
}
document.addEventListener('DOMContentLoaded', muatDataMahasiswa);