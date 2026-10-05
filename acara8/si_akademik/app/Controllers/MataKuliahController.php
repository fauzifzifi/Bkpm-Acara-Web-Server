<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\MataKuliahModel;
use App\Models\ProdiModel;

class MataKuliahController extends Controller
{
    private MataKuliahModel $model;
    private ProdiModel $prodi;

    public function __construct()
    {
        $this->model = new MataKuliahModel();
        $this->prodi = new ProdiModel();
    }

    // GET /matakuliah
    public function index()
    {
        $this->view('matakuliah/index', [
            'title' => 'Mata Kuliah',
            'pager' => $this->model->paginate((int) ($_GET['page'] ?? 1)),
        ]);
    }

    // GET /matakuliah/create
    public function create()
    {
        $this->view('matakuliah/create', [
            'title'       => 'Tambah Mata Kuliah',
            'daftarProdi' => $this->prodi->all(),
        ]);
    }

    // POST /matakuliah
    public function store()
    {
        $data   = $this->input();
        $errors = $this->validate($data);
        if ($errors) {
            $this->failWithOld($errors, '/matakuliah/create');
        }

        try {
            $this->model->create($data);
        } catch (\PDOException $e) {
            $this->failWithOld(
                $this->dbMessage($e, "Kode {$data['kode']} sudah dipakai.", 'Data tidak dapat disimpan.'),
                '/matakuliah/create'
            );
        }

        $this->flash('Mata kuliah berhasil ditambahkan.');
        $this->redirect('/matakuliah');
    }

    // GET /matakuliah/{id}/edit
    public function edit(int $id)
    {
        $mk = $this->findOrFail($id);
        $this->view('matakuliah/edit', [
            'title'       => 'Edit Mata Kuliah',
            'mk'          => $mk,
            'daftarProdi' => $this->prodi->all(),
        ]);
    }

    // POST /matakuliah/{id}/update
    public function update(int $id)
    {
        $this->findOrFail($id);

        $data   = $this->input();
        $errors = $this->validate($data);
        if ($errors) {
            $this->failWithOld($errors, "/matakuliah/{$id}/edit");
        }

        try {
            $this->model->update($id, $data);
        } catch (\PDOException $e) {
            $this->failWithOld(
                $this->dbMessage($e, "Kode {$data['kode']} sudah dipakai mata kuliah lain.", 'Data tidak dapat disimpan.'),
                "/matakuliah/{$id}/edit"
            );
        }

        $this->flash('Mata kuliah berhasil diperbarui.');
        $this->redirect('/matakuliah');
    }

    // POST /matakuliah/{id}/delete
    public function destroy(int $id)
    {
        $this->findOrFail($id);

        try {
            $this->model->delete($id);
        } catch (\PDOException $e) {
            $this->flash($this->dbMessage($e, '', 'Mata kuliah tidak dapat dihapus karena masih dipakai.'), 'danger');
            $this->redirect('/matakuliah');
        }

        $this->flash('Mata kuliah berhasil dihapus.', 'warning');
        $this->redirect('/matakuliah');
    }

    private function findOrFail(int $id): array
    {
        $mk = $this->model->find($id);
        if ($mk === null) {
            $this->flash('Mata kuliah tidak ditemukan.', 'danger');
            $this->redirect('/matakuliah');
        }
        return $mk;
    }

    private function input(): array
    {
        return [
            'kode'     => strtoupper(trim($_POST['kode'] ?? '')),
            'nama'     => trim($_POST['nama'] ?? ''),
            'sks'      => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
        ];
    }

    private function validate(array $d): array
    {
        $errors = [];
        if ($d['kode'] === '' || str_len($d['kode']) > 10) {
            $errors[] = 'Kode wajib diisi (maksimal 10 karakter).';
        }
        if ($d['nama'] === '' || str_len($d['nama']) > 150) {
            $errors[] = 'Nama wajib diisi (maksimal 150 karakter).';
        }
        if ($d['sks'] < 1 || $d['sks'] > 6) {
            $errors[] = 'SKS harus antara 1 sampai 6.';
        }
        if ($d['prodi_id'] < 1 || $this->prodi->find($d['prodi_id']) === null) {
            $errors[] = 'Program studi wajib dipilih.';
        }
        return $errors;
    }
}
