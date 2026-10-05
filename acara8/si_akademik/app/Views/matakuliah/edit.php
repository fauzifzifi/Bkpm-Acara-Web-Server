<h1 class="mb-4">Edit Mata Kuliah</h1>
<?php
$action = url('/matakuliah/' . $mk['id'] . '/update');
$submit = 'Update';
include __DIR__ . '/form.php';
