if (!localStorage.getItem('token')) {
    window.location.href = 'login.html';
} else {
    document.querySelector('#logout').addEventListener('click', () => {
        localStorage.removeItem('token');
        localStorage.removeItem('role');
        window.location.href = 'login.html';
    });

    const BASE_URL = 'http://localhost/acara_25/public';
    const totalMahasiswa = document.querySelector('#total-mahasiswa');

    async function muatRingkasan() {
        try {
            const response = await fetch(`${BASE_URL}/mahasiswa`);
            const hasil = await response.json();

            if (!response.ok || !hasil.success || !Array.isArray(hasil.data)) {
                throw new Error(hasil.message || 'Data mahasiswa tidak valid');
            }

            totalMahasiswa.textContent = hasil.data.length;
        } catch (error) {
            console.error('Gagal memuat ringkasan mahasiswa:', error);
            totalMahasiswa.textContent = 'Gagal dimuat';
        }
    }

    muatRingkasan();
}
