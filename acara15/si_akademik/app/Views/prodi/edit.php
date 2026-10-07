<h1 class="mb-4">Edit Program Studi</h1>
<?php
$action = url('/prodi/' . $prodi['id'] . '/update');
$submit = 'Update';
include __DIR__ . '/form.php';
