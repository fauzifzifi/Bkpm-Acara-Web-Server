<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Exceptions\RepositoryException;
use App\Repositories\ProdiRepository;

class ProdiController extends BaseController
{
    private ProdiRepository $repo;

    // Repository disuntikkan lewat constructor (Dependency Injection)
    public function __construct(ProdiRepository $repo)
    {
        $this->repo = $repo;
    }

    // GET /prodi
    public function index()
    {
        $this->view('prodi/index', [
            'title' => 'Program Studi',
            'pager' => $this->repo->paginate((int) ($_GET['page'] ?? 1)),
        ]);
    }

    // GET /prodi/create
    public function create()
    {
        $this->view('prodi/create', ['title' => 'Tambah Program Studi']);
    }

    // POST /prodi
    public function store()
    {
        $data   = $this->input();
        $errors = $this->validate($data);
        if ($errors) {
            $this->failWithOld($errors, '/prodi/create');
        }

        try {
            $this->repo->create($data);
        } catch (RepositoryException $e) {
            $this->failWithOld(
                $this->repoMessage($e, "Kode {$data['kode']} sudah dipakai.", 'Data tidak dapat disimpan.'),
                '/prodi/create'
            );
        }

        $this->flash('Program studi berhasil ditambahkan.');
        $this->redirect('/prodi');
    }

    // GET /prodi/{id}/edit
    public function edit(int $id)
    {
        $prodi = $this->findOrFail($id);
        $this->view('prodi/edit', ['title' => 'Edit Program Studi', 'prodi' => $prodi]);
    }

    // POST /prodi/{id}/update
    public function update(int $id)
    {
        $this->findOrFail($id);

        $data   = $this->input();
        $errors = $this->validate($data);
        if ($errors) {
            $this->failWithOld($errors, "/prodi/{$id}/edit");
        }

        try {
            $this->repo->update($id, $data);
        } catch (RepositoryException $e) {
            $this->failWithOld(
                $this->repoMessage($e, "Kode {$data['kode']} sudah dipakai prodi lain.", 'Data tidak dapat disimpan.'),
                "/prodi/{$id}/edit"
            );
        }

        $this->flash('Program studi berhasil diperbarui.');
        $this->redirect('/prodi');
    }

    // POST /prodi/{id}/delete
    public function destroy(int $id)
    {
        $this->findOrFail($id);

        try {
            $this->repo->delete($id);
        } catch (RepositoryException $e) {
            $this->flash($this->repoMessage(
                $e,
                '',
                'Prodi tidak dapat dihapus karena masih dipakai oleh mahasiswa atau mata kuliah.'
            ), 'danger');
            $this->redirect('/prodi');
        }

        $this->flash('Program studi berhasil dihapus.', 'warning');
        $this->redirect('/prodi');
    }

    private function findOrFail(int $id): array
    {
        $prodi = $this->repo->find($id);
        if ($prodi === null) {
            $this->flash('Program studi tidak ditemukan.', 'danger');
            $this->redirect('/prodi');
        }
        return $prodi;
    }

    private function input(): array
    {
        return [
            'kode' => strtoupper(trim($_POST['kode'] ?? '')),
            'nama' => trim($_POST['nama'] ?? ''),
        ];
    }

    private function validate(array $d): array
    {
        $errors = [];
        if ($d['kode'] === '' || str_len($d['kode']) > 10) {
            $errors[] = 'Kode wajib diisi (maksimal 10 karakter).';
        }
        if ($d['nama'] === '' || str_len($d['nama']) > 100) {
            $errors[] = 'Nama wajib diisi (maksimal 100 karakter).';
        }
        return $errors;
    }
}
