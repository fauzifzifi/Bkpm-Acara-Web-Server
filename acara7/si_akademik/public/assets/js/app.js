// Tutup flash message otomatis setelah 4 detik.
document.querySelectorAll('.alert-dismissible').forEach(function (el) {
    setTimeout(function () {
        bootstrap.Alert.getOrCreateInstance(el).close();
    }, 4000);
});
