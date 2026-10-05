// Tutup flash message otomatis setelah 4 detik.
document.querySelectorAll('.alert-dismissible').forEach(function (el) {
    setTimeout(function () {
        bootstrap.Alert.getOrCreateInstance(el).close();
    }, 4000);
});

// Konfirmasi sebelum hapus: <form data-confirm="Yakin hapus ...?">
document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
        if (!confirm(form.dataset.confirm)) {
            e.preventDefault();
        }
    });
});
