<h1 class="mb-4">Edit Mahasiswa</h1>
<?php
$action = url('/mahasiswa/' . $mhs->getId() . '/update');
$submit = 'Update';
include __DIR__ . '/form.php';
