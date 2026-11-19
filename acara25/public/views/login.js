const BASE_URL = 'http://localhost/acara_25/public';

document.querySelector('#form-login').addEventListener('submit', async (e) => {
    e.preventDefault();

    const form = e.currentTarget;
    const username = form.elements.username.value;
    const password = form.elements.password.value;

    try {
        const res = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password }),
        });
        const hasil = await res.json();

        if (hasil.success) {
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role);
            window.location.href = 'dashboard.html';
        } else {
            alert(hasil.message);
        }
    } catch (error) {
        console.error('Gagal melakukan login:', error);
        alert('Gagal terhubung ke server');
    }
});
