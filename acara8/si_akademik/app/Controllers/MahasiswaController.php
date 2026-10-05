<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\MahasiswaModel;
use App\Models\ProdiModel;

class MahasiswaController extends Controller
{
    private const STATUS = ['aktif', 'cuti', 'lulus'];

    private MahasiswaModel $model;
    private ProdiModel $prodi;

    public function __construct()
    {
        $this->model = new MahasiswaModel();
        $this->prodi = new ProdiModel();
    }

    // GET /mahasiswa?q=...&page=...  (daftar + pencarian + pagination)
    public function index()
    {
        $search = trim($_GET['q'] ?? '');
        $page   = (int) ($_GET['page'] ?? 1);

        $this->view('mahasiswa/index', [
            'title'  => 'Daftar Mahasiswa',
            'pager'  => $this->model->paginate($search, $page),
            'search' => $search,
        ]);
    }

    // GET /mahasiswa/create
    public function create()
    {
        $this->view('mahasiswa/create', $this->formData('Tambah Mahasiswa'));
    }

    // POST /mahasiswa
    public function store()
    {
        $data   = $this->input();
        $errors = $this->validate($data);
        if ($errors) {
            $this->failWithOld($errors, '/mahasiswa/create');
        }

        try {
            $this->model->create($data);
        } catch (\PDOException $e) {
            $this->failWithOld(
                $this->dbMessage($e, "NIM {$data['nim']} sudah terdaftar.", 'Data tidak dapat disimpan.'),
                '/mahasiswa/create'
            );
        }

        $this->flash('Data mahasiswa berhasil ditambahkan.');
        $this->redirect('/mahasiswa');
    }

    // GET /mahasiswa/{id}/edit
    public function edit(int $id)
    {
        $mhs = $this->findOrFail($id);
        $this->view('mahasiswa/edit', $this->formData('Edit Mahasiswa') + ['mhs' => $mhs]);
    }

    // POST /mahasiswa/{id}/update
    public function update(int $id)
    {
        $this->findOrFail($id);

        $data   = $this->input();
        $errors = $this->validate($data);
        if ($errors) {
            $this->failWithOld($errors, "/mahasiswa/{$id}/edit");
        }

        try {
            $this->model->update($id, $data);
        } catch (\PDOException $e) {
            $this->failWithOld(
                $this->dbMessage($e, "NIM {$data['nim']} sudah dipakai mahasiswa lain.", 'Data tidak dapat disimpan.'),
                "/mahasiswa/{$id}/edit"
            );
        }

        $this->flash('Data mahasiswa berhasil diperbarui.');
        $this->redirect('/mahasiswa');
    }

    // POST /mahasiswa/{id}/delete
    public function destroy(int $id)
    {
        $this->findOrFail($id);

        try {
            $this->model->delete($id);
        } catch (\PDOException $e) {
            $this->flash($this->dbMessage($e, '', 'Mahasiswa tidak dapat dihapus karena masih dipakai.'), 'danger');
            $this->redirect('/mahasiswa');
        }

        $this->flash('Data mahasiswa berhasil dihapus.', 'warning');
        $this->redirect('/mahasiswa');
    }

    // ---------- helper ----------
    private function formData(string $title): array
    {
        return [
            'title'        => $title,
            'daftarProdi'  => $this->prodi->all(),
            'statusList'   => self::STATUS,
        ];
    }

    private function findOrFail(int $id): array
    {
        $mhs = $this->model->find($id);
        if ($mhs === null) {
            $this->flash('Mahasiswa tidak ditemukan.', 'danger');
            $this->redirect('/mahasiswa');
        }
        return $mhs;
    }

    private function input(): array
    {
        return [
            'nim'      => trim($_POST['nim'] ?? ''),
            'nama'     => trim($_POST['nama'] ?? ''),
            'email'    => trim($_POST['email'] ?? ''),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
            'angkatan' => (int) ($_POST['angkatan'] ?? 0),
            'status'   => $_POST['status'] ?? '',
        ];
    }

    private function validate(array $d): array
    {
        $errors = [];

        if (!preg_match('/^\d{4,20}$/', $d['nim'])) {
            $errors[] = 'NIM wajib diisi, berupa angka 4-20 digit.';
        }
        if ($d['nama'] === '' || str_len($d['nama']) > 100) {
            $errors[] = 'Nama wajib diisi (maksimal 100 karakter).';
        }
        if ($d['email'] !== '' && (!filter_var($d['email'], FILTER_VALIDATE_EMAIL) || str_len($d['email']) > 100)) {
            $errors[] = 'Format email tidak valid.';
        }
        if ($d['prodi_id'] < 1 || $this->prodi->find($d['prodi_id']) === null) {
            $errors[] = 'Program studi wajib dipilih.';
        }
        if ($d['angkatan'] < 1901 || $d['angkatan'] > (int) date('Y') + 1) {
            $errors[] = 'Angkatan tidak valid.';
        }
        if (!in_array($d['status'], self::STATUS, true)) {
            $errors[] = 'Status harus aktif, cuti, atau lulus.';
        }

        return $errors;
    }
}
